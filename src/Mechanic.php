<?php

namespace Pietroagazzi\Mechanic;

use Pietroagazzi\Mechanic\DI\Container;
use ReflectionException;

/**
 * Mechanic is the kernel of the framework.
 * It handles the request and the response.
 *
 * Secondary kernel should extend this class (e.g. ApiKernel)
 *
 * @author Pietro Agazzi <contact@agazzipietro.it>
 */
class Mechanic extends AbstractKernel
{
	public function __construct(
		protected ?Container     $container = null,
		protected ?Router\Router $router = null
	)
	{
		$this->container ??= new Container;
		$this->router ??= new Router\Router;
	}

	/**
	 * Add a route to the router with the GET method
	 *
	 * @see \Pietroagazzi\Mechanic\Router\Router::addRoute
	 */
	public function get(string $path, callable $handler): void
	{
		$this->router->addRoute(new Router\Route('GET', $path, $handler));
	}

	/**
	 * Add a route to the router with the POST method
	 *
	 * @see \Pietroagazzi\Mechanic\Router\Router::addRoute
	 */
	public function post(string $path, callable $handler): void
	{
		$this->router->addRoute(new Router\Route('POST', $path, $handler));
	}

	/**
	 * Add a route to the router with the PUT method
	 *
	 * @see \Pietroagazzi\Mechanic\Router\Router::addRoute
	 */
	public function put(string $path, callable $handler): void
	{
		$this->router->addRoute(new Router\Route('PUT', $path, $handler));
	}

	/**
	 * Handle the request and send the response.
	 * This method is the entry point of the framework, it should be called only once per request.
	 * It will try to match the request to a route and invoke the handler.
	 *
	 * - If no request is provided, the method will try to create one from the global variables.
	 * - If no route is found, a 404 response will be sent.
	 *
	 * @param Http\Request|null $request the request object to handle
	 * @throws ReflectionException if the handler is not callable
	 */
	public function handle(Http\Request $request = null): void
	{
		$request ??= $this->getRequest();

		$route = $this->router->match($request->getMethod(), $request->getPath());

		// If no route is found, send a 404 response
		if ($route === null) {
			self::sendNotFound();
			return;
		}

		// Invoke the handler of the route
		$this->container->invoke($route->getHandler())->send();
	}

	protected function getContainer(): Container
	{
		return $this->container;
	}

	protected function getRouter(): Router\Router
	{
		return $this->router;
	}
}