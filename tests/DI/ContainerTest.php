<?php

namespace Pietroagazzi\Mechanic\Tests\DI;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\DI\Container;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;

#[CoversClass(Container::class)]
#[UsesClass(Request::class)]
#[UsesClass(Response::class)]
class ContainerTest extends TestCase
{
	public function testGetAndSet(): void
	{
		$container = new Container();
		$container->set(Container::class);

		$this->assertInstanceOf(Container::class, $container->get(Container::class));
	}

	public function testGetAndSetWithClosure(): void
	{
		$container = new Container();
		$container->set(Container::class, function () {
			return new Container();
		});

		$this->assertInstanceOf(Container::class, $container->get(Container::class));
	}

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

	public function testGetAndSetWithClosureWithoutParameters(): void
	{
		$container = new Container();

		$container->set(Request::class, fn() => Request::createFromGlobals());

		$this->assertEquals(Request::createFromGlobals(), $container->get(Request::class));
	}

	public function testInvoke(): void
	{
		$container = new Container;

		$container->set(Request::class, fn() => Request::createFromGlobals());

		$closure = static function (Request $request) {
			return $request;
		};

		$this->assertEquals(Request::createFromGlobals(), $container->invoke($closure));
	}

	public function testInvokeWithParameters(): void
	{
		$container = new Container;

		$request = $this->createStub(Request::class);
		$request->method('getPath')->willReturn('Hello');

		$container->set(Request::class, fn() => $request);

		$closure = static function (Request $request, string $a, string $b) {
			return "{$request->getPath()} $a$b";
		};

		$this->assertEquals('Hello World!', $container->invoke($closure, ['a' => 'World', 'b' => '!']));
	}
}
