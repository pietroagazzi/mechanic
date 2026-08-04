<?php

namespace Pietroagazzi\Mechanic\DI;


use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;
use RuntimeException;

/**
 * Dependency Injection Container.
 * This class uses the Reflection API to resolve dependencies and invoke callables.
 *
 * @todo: Should implement PSR-11 ContainerInterface
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
     * @param array<string, mixed> $parameters The parameters to pass to the callable
	 * @throws ReflectionException if the callable does not exist or is not a callable
	 */
	public function invoke(Closure $callable, array $parameters = []): mixed
	{
		$reflector = new ReflectionFunction($callable);

		return $reflector->invokeArgs($this->resolveParameters($reflector->getParameters(), $parameters));
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

		$constructor = $reflector->getConstructor();

		if ($constructor === null) {
			return $reflector->newInstance();
		}

		return $reflector->newInstanceArgs($this->resolveParameters($constructor->getParameters(), $parameters));
	}

	/**
	 * Resolve reflected parameters into concrete arguments.
	 *
	 * Each parameter is resolved, in order of precedence, from the given
	 * parameters (matched by name), its own default value, then the container.
	 *
	 * @param ReflectionParameter[] $parameters The parameters to resolve
	 * @param array<string, mixed> $given The parameters provided by the caller
	 *
	 * @return list<mixed>
	 * @throws ReflectionException if a dependency does not exist
	 */
	private function resolveParameters(array $parameters, array $given): array
	{
		return array_map(function (ReflectionParameter $parameter) use ($given): mixed {
			$name = $parameter->getName();

			if (array_key_exists($name, $given)) {
				return $given[$name];
			}

			if ($parameter->isDefaultValueAvailable()) {
				return $parameter->getDefaultValue();
			}

			$type = $parameter->getType();

			// only class types can be autowired
			if (!$type instanceof ReflectionNamedType || $type->isBuiltin()) {
				throw new RuntimeException("Can not resolve parameter \$$name");
			}

			return $this->get($type->getName());
		}, $parameters);
	}

	/**
	 * Return true if the container has the given service
	 */
	public function has(string $id): bool
	{
		return isset($this->instances[$id]);
	}
}