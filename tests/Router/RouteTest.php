<?php

namespace Router;

use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Router\Route;

class RouteTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\Router\Route::__construct
	 * @covers \Pietroagazzi\Mechanic\Router\Route
	 */
	public function testGetPath(): void
	{
		$route = new Route('/test');
		$this->assertEquals('/test', $route->getPath());
	}
}
