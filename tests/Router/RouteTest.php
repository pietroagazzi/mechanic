<?php

namespace Pietroagazzi\Mechanic\Tests\Router;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Router\Route;

#[CoversClass(Route::class)]
class RouteTest extends TestCase
{
	public function testGetMethod(): void
	{
		$route = new Route('GET', '/', fn() => null);

		$this->assertEquals('GET', $route->getMethod());
	}

	public function testGetPath(): void
	{
		$route = new Route('GET', '/', fn() => null);

		$this->assertEquals('/', $route->getPath());
	}
}
