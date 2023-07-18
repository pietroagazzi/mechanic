<?php

namespace Http;

use Pietroagazzi\Mechanic\Http\Response;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;
use function xdebug_get_headers;

class ResponseTest extends TestCase
{
    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::__construct
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     */
    public function test__construct(): void
    {
        $response = new Response(content: 'Hello World', status: 501, headers: ['foo' => 'bar']);

        $this->assertEquals('Hello World', $response->getContent());
        $this->assertEquals(501, $response->getStatus());
        $this->assertEquals(['foo' => 'bar'], $response->getHeaders());
    }

    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::sendHeaders
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     */
    public function testSendHeaders(): void
    {
        $response = new Response(content: 'Hello World', status: 501, headers: ['foo' => 'bar']);
        $response->sendHeaders();

        $headers = xdebug_get_headers();

        $this->assertEquals(501, http_response_code());

        $this->assertContains('foo: bar', $headers);
    }

    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::sendContent
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     */
    public function testSendContent(): void
    {
        $response = new Response(content: 'Hello World', status: 501, headers: ['foo' => 'bar']);
        $response->sendContent();

        $this->expectOutputString('Hello World');
    }

    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::send
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     */
    public function testSend(): void
    {
        $response = new Response(content: 'Hello World', status: 501, headers: ['foo' => 'bar']);
        $response->send();

        $headers = xdebug_get_headers();

        $this->assertEquals(501, http_response_code());

        $this->assertContains('foo: bar', $headers);

        $this->expectOutputString('Hello World');
    }

    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::getStatusCodeText
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     * @uses   \Pietroagazzi\Mechanic\Http\ResponseStatusCodeTrait
     */
    public function testGetStatusCodeText(): void
    {
        $this->assertEquals('OK', Response::getStatusCodeText(200));
        $this->assertEquals('Not Found', Response::getStatusCodeText(404));
        $this->assertEquals('Internal Server Error', Response::getStatusCodeText(500));

        // Test with an invalid status code
        $this->expectException(InvalidArgumentException::class);

        Response::getStatusCodeText(999);
    }

    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::getStatus
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     */
    public function testGetStatus(): void
    {
        $response = new Response();
        $this->assertEquals(200, $response->getStatus());
    }

    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::getContent
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     */
    public function testGetContent(): void
    {
        $response = new Response(content: 'Hello World');
        $this->assertEquals('Hello World', $response->getContent());
    }

    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::getHeaders
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     */
    public function testGetHeaders(): void
    {
        $response = new Response(headers: ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $response->getHeaders());
    }

    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::setStatus
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     */
    public function testSetStatus(): void
    {
        $response = new Response();
        $response->setStatus(404);
        $this->assertEquals(404, $response->getStatus());
    }

    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::setContent
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     */
    public function testSetContent(): void
    {
        $response = new Response();
        $response->setContent('Hello World');
        $this->assertEquals('Hello World', $response->getContent());
    }

    /**
     * @covers \Pietroagazzi\Mechanic\Http\Response::setHeaders
     * @return void
     * @uses   \Pietroagazzi\Mechanic\Http\Response
     */
    public function testSetHeaders(): void
    {
        $response = new Response();
        $response->setHeaders(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $response->getHeaders());
    }
}
