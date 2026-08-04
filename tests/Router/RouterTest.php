<?php

namespace Pietroagazzi\Mechanic\Tests\Router;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Router\Route;
use Pietroagazzi\Mechanic\Router\RouteCollection;
use Pietroagazzi\Mechanic\Router\Router;
use RuntimeException;

#[CoversClass(Router::class)]
#[UsesClass(Route::class)]
#[UsesClass(RouteCollection::class)]
class RouterTest extends TestCase
{
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
