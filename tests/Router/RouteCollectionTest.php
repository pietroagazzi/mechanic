<?php

namespace Router;

use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Router\Route;
use Pietroagazzi\Mechanic\Router\RouteCollection;

class RouteCollectionTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\Router\RouteCollection::addRoute
	 * @covers \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 */
	public function testAddRoute(): void
	{
		$collection = new RouteCollection();
		$collection->addRoute('test', new Route('test'));
		$this->assertArrayHasKey('test', $collection->getRoutes());
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\Router\RouteCollection::getRoute
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 */
	public function testGetRoute(): void
	{
		$collection = new RouteCollection();
		$collection->addRoute('test', new Route('test'));
		$this->assertEquals('test', $collection->getRoute('test')->getPath());
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\Router\RouteCollection::getRoutes
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 */
	public function testGetRoutes(): void
	{
		$collection = new RouteCollection();
		$collection->addRoute('test', new Route('test'));
		$this->assertIsArray($collection->getRoutes());
	}
}
