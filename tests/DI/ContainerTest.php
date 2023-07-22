<?php

namespace DI;

use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\DI\Container;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;

class ContainerTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\DI\Container::set
	 * @covers \Pietroagazzi\Mechanic\DI\Container::get
	 * @covers \Pietroagazzi\Mechanic\DI\Container::resolve
	 * @uses   \Pietroagazzi\Mechanic\DI\Container
	 */
	public function testGetAndSet(): void
	{
		$container = new Container();
		$container->set(Container::class);

		$this->assertInstanceOf(Container::class, $container->get(Container::class));
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\DI\Container::set
	 * @covers \Pietroagazzi\Mechanic\DI\Container::get
	 * @uses   \Pietroagazzi\Mechanic\DI\Container
	 */
	public function testGetAndSetWithClosure(): void
	{
		$container = new Container();
		$container->set(Container::class, function () {
			return new Container();
		});

		$this->assertInstanceOf(Container::class, $container->get(Container::class));
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\DI\Container::set
	 * @covers \Pietroagazzi\Mechanic\DI\Container::get
	 * @uses   \Pietroagazzi\Mechanic\DI\Container
	 * @uses   \Pietroagazzi\Mechanic\Http\Response
	 */
	public function testGetAndSetWithClosureAndParameters(): void
	{
		$container = new Container();

		$container->set(Response::class, function ($container, $parameters) {
			return new Response($parameters['body'], $parameters['status']);
		});

		/** @var Response $response */
		$response = $container->get(Response::class, [
			'body' => 'Hello World',
			'status' => 200,
		]);

		$this->assertInstanceOf(Response::class, $response);
		$this->assertEquals('Hello World', $response->getContent());
		$this->assertEquals(200, $response->getStatus());
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\DI\Container::set
	 * @covers \Pietroagazzi\Mechanic\DI\Container::get
	 * @uses   \Pietroagazzi\Mechanic\DI\Container
	 * @uses   \Pietroagazzi\Mechanic\Http\Request
	 */
	public function testGetAndSetWithClosureWithoutParameters(): void
	{
		$container = new Container();

		$container->set(Request::class, fn() => Request::createFromGlobals());

		$this->assertEquals(Request::createFromGlobals(), $container->get(Request::class));
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\DI\Container::invoke
	 * @uses   \Pietroagazzi\Mechanic\DI\Container
	 * @uses   \Pietroagazzi\Mechanic\Http\Request
	 */
	public function testInvoke(): void
	{
		$container = new Container;

		$container->set(Request::class, fn() => Request::createFromGlobals());

		$closure = static function (Request $request) {
			return $request;
		};

		$this->assertEquals(Request::createFromGlobals(), $container->invoke($closure));
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\DI\Container::invoke
	 * @uses   \Pietroagazzi\Mechanic\DI\Container
	 * @uses   \Pietroagazzi\Mechanic\Http\Request
	 */
	public function testInvokeWithParameters(): void
	{
		$container = new Container;

		$request = $this->createMock(Request::class);
		$request->method('getPath')->willReturn('Hello');

		$container->set(Request::class, fn() => $request);

		$closure = static function (Request $request, string $a, string $b) {
			return "{$request->getPath()} $a$b";
		};

		$this->assertEquals('Hello World!', $container->invoke($closure, ['a' => 'World', 'b' => '!']));
	}
}
