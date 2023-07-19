<?php

namespace Router;

use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Router\Route;
use Pietroagazzi\Mechanic\Router\RouteCollection;
use Pietroagazzi\Mechanic\Router\RouteNotFoundException;
use Pietroagazzi\Mechanic\Router\UrlMatcher;

class UrlMatcherTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\Router\UrlMatcher::match
	 * @covers \Pietroagazzi\Mechanic\Router\UrlMatcher::__construct
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 * @uses   \Pietroagazzi\Mechanic\Router\UrlMatcher
	 */
	public function testMatch(): void
	{
		$route = new Route('/path/to');
		$collection = (new RouteCollection())->addRoute('test', $route);
		$matcher = new UrlMatcher($collection);
		$this->assertEquals($route, $matcher->match('/path/to'));
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\Router\UrlMatcher::match
	 * @throws RouteNotFoundException
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 * @uses   \Pietroagazzi\Mechanic\Router\UrlMatcher
	 */
	public function testMatchNotFound(): void
	{
		$this->expectException(RouteNotFoundException::class);
		$collection = (new RouteCollection())->addRoute('test', new Route('test'));
		$matcher = new UrlMatcher($collection);
		$matcher->match('not-found');
	}
}
