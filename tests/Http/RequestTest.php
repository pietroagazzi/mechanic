<?php

namespace Pietroagazzi\Mechanic\Tests\Http;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Http\Request;

#[CoversClass(Request::class)]
class RequestTest extends TestCase
{
	/**
	 * Mock the global variables
	 */
	public function setUp(): void
	{
		$_POST = [
			'post_foo' => 'post_bar',
		];
		$_GET = [
			'get_foo' => 'get_bar',
		];
		$_COOKIE = [
			'cookie_foo' => 'cookie_bar'
		];
		$_SERVER = [
			'REQUEST_METHOD' => 'PUT',
			'SERVER_NAME' => 'server_name',
			'SERVER_PORT' => 'server_port',
			'REQUEST_URI' => '/path/to/resource'
		];
	}

	/**
	 * Test the creation of a Request object from the global variables
	 */
	public function testCreateFromGlobals(): void
	{
		$request = Request::createFromGlobals();

		$this->assertEquals($_POST, $request->getBody());
		$this->assertEquals($_GET, $request->getQuery());
		$this->assertEquals($_COOKIE, $request->getCookies());
		$this->assertEquals($_SERVER, $request->getServer());
	}

	public function testGetMethod(): void
	{
		$request = Request::createFromGlobals();
		$this->assertEquals('PUT', $request->getMethod());
	}

	public function testGetUri(): void
	{
		$request = Request::createFromGlobals();
		$this->assertEquals('/path/to/resource', $request->getUri());
	}

	public function testGet(): void
	{
		$request = Request::createFromGlobals();
		$this->assertEquals('post_bar', $request->get('post_foo'));
		$this->assertEquals('default', $request->get('not_existing', 'default'));
		$this->assertNull($request->get('not_existing'));
	}

	public function test__construct(): void
	{
		$request = new Request(
			['foo' => 'bar'],
			['foo' => 'bar'],
			['foo' => 'bar'],
			['foo' => 'bar']
		);

		$this->assertEquals(['foo' => 'bar'], $request->getBody());
		$this->assertEquals(['foo' => 'bar'], $request->getQuery());
		$this->assertEquals(['foo' => 'bar'], $request->getCookies());
		$this->assertEquals(['foo' => 'bar'], $request->getServer());
	}

	public function testGetPath(): void
	{
		$request = Request::createFromGlobals();
		$this->assertEquals('/path/to/resource', $request->getPath());
	}
}
