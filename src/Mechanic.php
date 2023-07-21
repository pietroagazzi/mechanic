<?php

namespace Pietroagazzi\Mechanic;

class Mechanic extends AbstractKernel
{
	public function __construct(
		private ?Router\Router $router = null
	)
	{
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

		$route->invoke()->send();
	}
}