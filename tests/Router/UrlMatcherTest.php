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
		$collection = (new RouteCollection())->addRoute('test', new Route('test'));
		$matcher = new UrlMatcher($collection);
		$this->assertEquals('test', $matcher->match('test')->getPath());
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
