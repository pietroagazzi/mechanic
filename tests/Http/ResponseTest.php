<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic\Tests\Http;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Http\Response;
use Pietroagazzi\Mechanic\Http\ResponseStatusCodeTrait;

#[CoversClass(Response::class)]
#[UsesTrait(ResponseStatusCodeTrait::class)]
final class ResponseTest extends TestCase
{
    public function test__construct(): void
    {
        $response = new Response(content: 'Hello World', status: 501, headers: ['foo' => 'bar']);

        $this->assertSame('Hello World', $response->getContent());
        $this->assertSame(501, $response->getStatus());
        $this->assertEquals(['foo' => 'bar'], $response->getHeaders());
    }

    public function testSendContent(): void
    {
        $response = new Response(content: 'Hello World', status: 501, headers: ['foo' => 'bar']);
        $response->sendContent();

        $this->expectOutputString('Hello World');
    }

    public function testSend(): void
    {
        $response = new Response(content: 'Hello World', status: 501, headers: ['foo' => 'bar']);
        $response->send();

        $this->assertEquals(501, http_response_code());
        $this->expectOutputString('Hello World');
    }

    public function testGetStatusCodeText(): void
    {
        $this->assertSame('OK', Response::getStatusCodeText(200));
        $this->assertSame('Not Found', Response::getStatusCodeText(404));
        $this->assertSame('Internal Server Error', Response::getStatusCodeText(500));

        // Test with an invalid status code
        $this->expectException(InvalidArgumentException::class);

        Response::getStatusCodeText(999);
    }

    public function testGetStatus(): void
    {
        $response = new Response();
        $this->assertSame(200, $response->getStatus());
    }

    public function testGetContent(): void
    {
        $response = new Response(content: 'Hello World');
        $this->assertSame('Hello World', $response->getContent());
    }

    public function testGetHeaders(): void
    {
        $response = new Response(headers: ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $response->getHeaders());
    }

    public function testSetStatus(): void
    {
        $response = new Response();
        $response->setStatus(404);
        $this->assertSame(404, $response->getStatus());
    }

    public function testSetContent(): void
    {
        $response = new Response();
        $response->setContent('Hello World');
        $this->assertSame('Hello World', $response->getContent());
    }

    public function testSetHeaders(): void
    {
        $response = new Response();
        $response->setHeaders(['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $response->getHeaders());
    }
}
