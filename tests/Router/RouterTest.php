<?php

namespace Router;

use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Router\Route;
use Pietroagazzi\Mechanic\Router\Router;
use RuntimeException;

class RouterTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\Router\Router::addRoute
	 * @covers \Pietroagazzi\Mechanic\Router\Router::match
	 * @covers \Pietroagazzi\Mechanic\Router\Router::__construct
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 */
	public function testRoute(): void
	{
		$router = new Router;

		$route = new Route(
			method: 'GET',
			path: '/',
			handler: fn() => 'Hello World'
		);

		$router->addRoute($route);

		$this->assertEquals(
			$route,
			$router->match('GET', '/')
		);
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\Router\Router::addRoute
	 * @uses   \Pietroagazzi\Mechanic\Router\Router
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 */
	public function testAddRouteAlreadyExists(): void
	{
		$router = new Router;

		$route = new Route(
			method: 'GET',
			path: '/',
			handler: fn() => 'Hello World'
		);

		$router->addRoute($route);

		$this->expectException(RuntimeException::class);
		$this->expectExceptionMessage('Route already exists');

		$router->addRoute($route);
	}
}
