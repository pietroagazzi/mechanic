<?php

namespace Pietroagazzi\Mechanic;

use Pietroagazzi\Mechanic\DI\Container;

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

	public function get(string $path, callable $handler): void
	{
		$this->router->addRoute(new Router\Route('GET', $path, $handler));
	}

	public function post(string $path, callable $handler): void
	{
		$this->router->addRoute(new Router\Route('POST', $path, $handler));
	}

	public function put(string $path, callable $handler): void
	{
		$this->router->addRoute(new Router\Route('PUT', $path, $handler));
	}

	public function handle(Http\Request $request = null): void
	{
		$request ??= $this->getRequest();

		$route = $this->router->match($request->getMethod(), $request->getPath());

		if ($route === null) {
			self::sendNotFound();
			return;
		}

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