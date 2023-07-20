<?php

namespace Router;

use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Router\Route;
use Pietroagazzi\Mechanic\Router\RouteCollection;

class RouteCollectionTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\Router\RouteCollection::add
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 */
	public function testAdd(): void
	{
		$collection = new RouteCollection;
		$route = new Route('GET', '/path/to/resource', fn() => 'foo');
		$collection->add($route);

		$this->assertCount(1, $collection);
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\Router\RouteCollection::has
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 */
	public function testHas(): void
	{
		$collection = new RouteCollection;
		$route = new Route('GET', '/path/to/resource', fn() => 'foo');
		$collection->add($route);

		$this->assertTrue($collection->has('/path/to/resource'));
		$this->assertFalse($collection->has('/path/to/another/resource'));
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\Router\RouteCollection::get
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 */
	public function testGet(): void
	{
		$collection = new RouteCollection;
		$route = new Route('GET', '/path/to/resource', fn() => 'foo');
		$collection->add($route);

		$this->assertSame($route, $collection->get('/path/to/resource'));
		$this->assertFalse($collection->get('/path/to/another/resource'));
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\Router\RouteCollection::getRoutes
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 */
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
