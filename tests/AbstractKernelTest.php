<?php


use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\AbstractKernel;

class AbstractKernelTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\AbstractKernel::sendNotFound
	 * @uses   \Pietroagazzi\Mechanic\Http\Response
	 */
	public function testSendNotFound(): void
	{
		$this->expectOutputString('Not Found');

		AbstractKernel::sendNotFound();

		$this->assertEquals(404, http_response_code());
	}

	/**
	 * @covers \Pietroagazzi\Mechanic\AbstractKernel::getRequest
	 * @uses   \Pietroagazzi\Mechanic\Http\Request
	 * @uses   \Pietroagazzi\Mechanic\Mechanic
	 * @uses   \Pietroagazzi\Mechanic\Router\Router
	 */
	public function testGetRequest(): void
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';
		$_SERVER['REQUEST_URI'] = '/';

		$request = (new class extends Pietroagazzi\Mechanic\Mechanic {
		})->getRequest();

		$this->assertEquals('GET', $request->getMethod());
		$this->assertEquals('/', $request->getPath());
	}
}
