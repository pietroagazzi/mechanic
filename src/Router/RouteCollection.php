<?php

namespace Pietroagazzi\Mechanic\Router;

class RouteCollection
{
	/**
	 * @var array<string, Route> $routes
	 */
	private array $routes = [];

	public function addRoute(string $name, Route $route): static
	{
		$this->routes[$name] = $route;
		return $this;
	}

	public function getRoute(string $name): Route
	{
		return $this->routes[$name];
	}

	/**
	 * @return Route[]
	 */
	public function getRoutes(): array
	{
		return $this->routes;
	}
}