<?php

namespace Pietroagazzi\Mechanic\Router;

use ArrayObject;

class RouteCollection extends ArrayObject
{
	public function add(Route $route): void
	{
		$this->append($route);
	}

	public function has(string $path): bool
	{
		return $this->get($path) !== false;
	}

	public function get(string $path): Route|false
	{
		foreach ($this as $route) {
			if ($route->getPath() === $path) {
				return $route;
			}
		}

		return false;
	}

	public function getRoutes(): array
	{
		return $this->getArrayCopy();
	}
}