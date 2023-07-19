<?php


use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Kernel;

class KernelTest extends TestCase
{
	/**
	 * @covers \Pietroagazzi\Mechanic\Kernel::handle
	 * @uses   \Pietroagazzi\Mechanic\Http\Request
	 * @uses   \Pietroagazzi\Mechanic\Http\Response
	 */
	public function testHandle(): void
	{
		$request = new Request([], [], [], ['REQUEST_METHOD' => 'TEST']);

		$kernel = new Kernel();
		$kernel->handle($request);

		$this->expectOutputString('TEST');
	}
}
