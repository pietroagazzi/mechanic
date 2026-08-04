<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic\Tests\DI;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\DI\Container;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;

#[CoversClass(Container::class)]
#[UsesClass(Request::class)]
#[UsesClass(Response::class)]
final class ContainerTest extends TestCase
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
        $container->set(Container::class, fn (): Container => new Container());

        $this->assertInstanceOf(Container::class, $container->get(Container::class));
    }

    public function testGetAndSetWithClosureAndParameters(): void
    {
        $container = new Container();

        $container->set(Response::class, static function (Container $container, array $parameters): Response {
            /** @var array{body: string, status: int} $parameters */
            return new Response($parameters['body'], $parameters['status']);
        });

        /** @var Response $response */
        $response = $container->get(Response::class, [
            'body' => 'Hello World',
            'status' => 200,
        ]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('Hello World', $response->getContent());
        $this->assertSame(200, $response->getStatus());
    }

    public function testGetAndSetWithClosureWithoutParameters(): void
    {
        $container = new Container();

        $container->set(Request::class, fn (): Request => Request::createFromGlobals());

        $this->assertEquals(Request::createFromGlobals(), $container->get(Request::class));
    }

    public function testInvoke(): void
    {
        $container = new Container();

        $container->set(Request::class, fn (): Request => Request::createFromGlobals());

        $closure = (static fn (Request $request): Request => $request);

        $this->assertEquals(Request::createFromGlobals(), $container->invoke($closure));
    }

    public function testInvokeWithParameters(): void
    {
        $container = new Container();

        $request = $this->createStub(Request::class);
        $request->method('getPath')->willReturn('Hello');

        $container->set(Request::class, fn (): Stub => $request);

        $closure = (static fn (Request $request, string $a, string $b): string => "{$request->getPath()} $a$b");

        $this->assertEquals('Hello World!', $container->invoke($closure, ['a' => 'World', 'b' => '!']));
    }
}
