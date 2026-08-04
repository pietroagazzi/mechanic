<?php

namespace Pietroagazzi\Mechanic\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\AbstractKernel;
use Pietroagazzi\Mechanic\DI\Container;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;
use Pietroagazzi\Mechanic\Http\ResponseStatusCodeTrait;
use Pietroagazzi\Mechanic\Mechanic;
use Pietroagazzi\Mechanic\Router\Route;
use Pietroagazzi\Mechanic\Router\RouteCollection;
use Pietroagazzi\Mechanic\Router\Router;

#[CoversClass(Mechanic::class)]
#[UsesClass(AbstractKernel::class)]
#[UsesClass(Container::class)]
#[UsesClass(Response::class)]
#[UsesClass(Route::class)]
#[UsesClass(RouteCollection::class)]
#[UsesClass(Router::class)]
#[UsesTrait(ResponseStatusCodeTrait::class)]
class MechanicTest extends TestCase
{
	/**
	 * @throws Exception
	 */
	public function testGet(): void
	{
		$mechanic = new Mechanic;
		$mechanic->get('/path/to', fn() => new Response('Hello World'));

		$request = $this->createStub(Request::class);
		$request->method('getMethod')->willReturn('GET');
		$request->method('getPath')->willReturn('/path/to');

		$mechanic->handle($request);

		$this->expectOutputString('Hello World');
	}

	/**
	 * @throws Exception
	 */
	public function testHandleNotFound(): void
	{
		$this->expectOutputString('Not Found');

		$mechanic = new Mechanic();
		$mechanic->get('/path/to', fn() => new Response('Hello World'));

		$request = $this->createStub(Request::class);
		$request->method('getMethod')->willReturn('GET');
		$request->method('getPath')->willReturn('/path/not/found');

		$mechanic->handle($request);

		$this->assertEquals(404, http_response_code());
	}
}
