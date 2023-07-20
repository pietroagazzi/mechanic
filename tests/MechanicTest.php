<?php


use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;
use Pietroagazzi\Mechanic\Mechanic;

class MechanicTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\Mechanic::get
	 * @covers \Pietroagazzi\Mechanic\Mechanic::__construct
	 * @covers \Pietroagazzi\Mechanic\Mechanic::handle
	 * @throws \PHPUnit\Framework\MockObject\Exception
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Mechanic
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 * @uses   \Pietroagazzi\Mechanic\Router\Router
	 * @uses   \Pietroagazzi\Mechanic\AbstractKernel
	 * @uses   \Pietroagazzi\Mechanic\Http\Response
	 */
	public function testGet(): void
	{
		$mechanic = new Mechanic();
		$mechanic->get('/path/to', fn() => new Response('Hello World'));

		$request = $this->createMock(Request::class);
		$request->method('getMethod')->willReturn('GET');
		$request->method('getPath')->willReturn('/path/to');

		$mechanic->handle($request);

		$this->expectOutputString('Hello World');
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\Mechanic::handle
	 * @throws \PHPUnit\Framework\MockObject\Exception
	 * @uses   \Pietroagazzi\Mechanic\Mechanic
	 * @uses   \Pietroagazzi\Mechanic\Router\Route
	 * @uses   \Pietroagazzi\Mechanic\Router\RouteCollection
	 * @uses   \Pietroagazzi\Mechanic\Router\Router
	 * @uses   \Pietroagazzi\Mechanic\AbstractKernel
	 * @uses   \Pietroagazzi\Mechanic\Http\Response
	 */
	public function testHandleNotFound(): void
	{
		$this->expectOutputString('Not Found');

		$mechanic = new Mechanic();
		$mechanic->get('/path/to', fn() => new Response('Hello World'));

		$request = $this->createMock(Request::class);
		$request->method('getMethod')->willReturn('GET');
		$request->method('getPath')->willReturn('/path/not/found');

		$mechanic->handle($request);

		$this->assertEquals(404, http_response_code());
	}
}
