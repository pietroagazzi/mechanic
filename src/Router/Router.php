<?php

namespace Pietroagazzi\Mechanic\Router;

use RuntimeException;

class Router
{
	public function __construct(
		private ?RouteCollection $routeCollection = null
	)
	{
		$this->routeCollection ??= new RouteCollection;
	}

	public function addRoute(Route $route): void
	{
		if ($this->match($route->getMethod(), $route->getPath()) !== null) {
			throw new RuntimeException('Route already exists');
		}

		$this->routeCollection->add($route);
	}

	public function match(string $method, string $path): ?Route
	{
		foreach ($this->routeCollection->getRoutes() as $route) {
			if ($route->getMethod() === $method && $route->getPath() === $path) {
				return $route;
			}
		}

		return null;
	}
}