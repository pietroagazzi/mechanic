<?php

namespace Pietroagazzi\Mechanic\DI;


use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionParameter;
use RuntimeException;

/**
 * Dependency Injection Container (PSR-11)
 * This class uses the Reflection API to resolve dependencies and invoke callables
 *
 * @see https://www.php-fig.org/psr/psr-11/
 * @see https://www.php.net/manual/en/book.reflection.php
 *
 * @author Pietro Agazzi <contact@agazzipietro.it>
 */
class Container
{
	/**
	 * The instances of the container
	 * @var array<string, mixed> $instances
	 */
	protected array $instances = [];

	/**
    	 *
	 * @param array<string, mixed> $parameters The parameters to pass to the callable
	 * @throws ReflectionException if the callable does not exist or is not a callable
	 */
	public function invoke(Closure $callable, array $parameters = []): mixed
	{
		$reflector = new ReflectionFunction($callable);

		$parameters = array_map(function (ReflectionParameter $parameter) use ($parameters) {
			$parameterType = $parameter->getType();
			if ($parameterType === null) {
				throw new RuntimeException("Can not resolve parameter $parameter->name");
			}

			$parameterName = $parameter->getName();
			if (isset($parameters[$parameterName])) {
				// if the parameter is passed by name, use the passed value
				return $parameters[$parameterName];
			}

			if ($parameter->isDefaultValueAvailable()) {
				// if the parameter has a default value, use it
				return $parameter->getDefaultValue();
			}

			// otherwise try to resolve it from the container
			/* @phpstan-ignore-next-line */
			return $this->get($parameterType->getName());
		}, $reflector->getParameters());

		return $reflector->invokeArgs($parameters);
	}

	/**
	 * Resolve a service from the container
	 *
	 * @param array<string, mixed> $parameters The parameters to pass to the constructor
	 * @throws ReflectionException if the class does not exist
	 */
	public function get(string $concrete, array $parameters = []): mixed
	{
		// if we don't have it, just register it
		if (!isset($this->instances[$concrete])) {
			$this->set($concrete);
		}

		return $this->resolve($this->instances[$concrete], $parameters);
	}

	/**
	 * Set a service in the container
	 *
	 * @param string $abstract The abstract name
	 * @param Closure|mixed|null $concrete The concrete class name or closure
	 */
	public function set(string $abstract, mixed $concrete = null): void
	{
		if ($concrete === null) {
			$concrete = $abstract;
		}
		$this->instances[$abstract] = $concrete;
	}

	/**
	 * Resolve a concrete class
	 *
	 * @param array<string, mixed> $parameters The parameters to pass to the constructor
	 * @throws ReflectionException if the class does not exist
	 */
	public function resolve(mixed $concrete, array $parameters = []): mixed
	{
		if ($concrete instanceof Closure) {
			return $concrete($this, $parameters);
		}

		$reflector = new ReflectionClass($concrete);

		// check if class is instantiable
		if (!$reflector->isInstantiable()) {
			throw new RuntimeException("Class $concrete is not instantiable");
		}

		// get class constructor
		$constructor = $reflector->getConstructor();

		if (is_null($constructor)) {
			// get new instance from class
			return $reflector->newInstance();
		}

		// get constructor params
		$parameters = $constructor->getParameters();
		$dependencies = $this->getDependencies($parameters);

		// get new instance with dependencies resolved
		return $reflector->newInstanceArgs($dependencies);
	}

	/**
	 * Get all dependencies resolved
	 *
	 * @param array<ReflectionParameter> $parameters The parameters to resolve
	 *
	 * @return ReflectionParameter[]
	 * @throws ReflectionException if the class does not exist
	 */
	public function getDependencies(array $parameters): array
	{
		$dependencies = [];
		foreach ($parameters as $parameter) {
			// get the type hinted class
			$dependency = $parameter->getType();
			if ($dependency === NULL) {
				// check if default value for a parameter is available
				if ($parameter->isDefaultValueAvailable()) {
					// get default value of parameter
					$dependencies[] = $parameter->getDefaultValue();
				} else {
					throw new \RuntimeException("Can not resolve class dependency $parameter->name");
				}
			} else {
				// get dependency resolved
				$dependencies[] = $this->get($dependency->getName());
			}
		}

		return $dependencies;
	}

	/**
	 * Return true if the container has the given service
	 */
	public function has(string $id): bool
	{
		return isset($this->instances[$id]);
	}
}