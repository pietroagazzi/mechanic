<?php

namespace Pietroagazzi\Mechanic\Router;

class UrlMatcher
{
	public function __construct(
		private readonly RouteCollection $routeCollection
	)
	{
	}

	/**
	 * @throws RouteNotFoundException If the route is not found
	 */
	public function match(string $path): Route
	{
		foreach ($this->routeCollection->getRoutes() as $route) {
			if ($route->getPath() === $path) {
				return $route;
			}
		}

		throw new RouteNotFoundException("Route not found for path $path");
	}
}