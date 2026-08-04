<?php

namespace Pietroagazzi\Mechanic\Tests\Router;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Router\Route;
use Pietroagazzi\Mechanic\Router\RouteCollection;

#[CoversClass(RouteCollection::class)]
#[UsesClass(Route::class)]
class RouteCollectionTest extends TestCase
{
	public function testAdd(): void
	{
		$collection = new RouteCollection;
		$route = new Route('GET', '/path/to/resource', fn() => 'foo');
		$collection->add($route);

		$this->assertCount(1, $collection);
	}

	public function testHas(): void
	{
		$collection = new RouteCollection;
		$route = new Route('GET', '/path/to/resource', fn() => 'foo');
		$collection->add($route);

		$this->assertTrue($collection->has('/path/to/resource'));
		$this->assertFalse($collection->has('/path/to/another/resource'));
	}

	public function testGet(): void
	{
		$collection = new RouteCollection;
		$route = new Route('GET', '/path/to/resource', fn() => 'foo');
		$collection->add($route);

		$this->assertSame($route, $collection->get('/path/to/resource'));
		$this->assertFalse($collection->get('/path/to/another/resource'));
	}

	public function testGetRoutes(): void
	{
		$collection = new RouteCollection;
		$routes = [
			new Route('GET', '/path/to/resource', fn() => 'foo'),
			new Route('GET', '/path/to/another/resource', fn() => 'bar'),
		];

		foreach ($routes as $route) {
			$collection->add($route);
		}

		$this->assertSame($routes, $collection->getRoutes());
	}
}
