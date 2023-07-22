<?php

namespace Router;

use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Router\Route;

class RouteTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\Router\Route::__construct
	 * @covers \Pietroagazzi\Mechanic\Router\Route::getMethod
	 */
	public function testGetMethod(): void
	{
		$route = new Route('GET', '/', fn() => null);

		$this->assertEquals('GET', $route->getMethod());
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\Router\Route::getPath
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 */
	public function testGetPath(): void
	{
		$route = new Route('GET', '/', fn() => null);

		$this->assertEquals('/', $route->getPath());
	}
}
