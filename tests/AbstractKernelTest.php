<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\AbstractKernel;
use Pietroagazzi\Mechanic\DI\Container;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;
use Pietroagazzi\Mechanic\Http\ResponseStatusCodeTrait;
use Pietroagazzi\Mechanic\Mechanic;
use Pietroagazzi\Mechanic\Router\RouteCollection;
use Pietroagazzi\Mechanic\Router\Router;

#[CoversClass(AbstractKernel::class)]
#[UsesClass(Container::class)]
#[UsesClass(Mechanic::class)]
#[UsesClass(Request::class)]
#[UsesClass(Response::class)]
#[UsesClass(RouteCollection::class)]
#[UsesClass(Router::class)]
#[UsesTrait(ResponseStatusCodeTrait::class)]
final class AbstractKernelTest extends TestCase
{
    public function testSendNotFound(): void
    {
        $this->expectOutputString('Not Found');

        AbstractKernel::sendNotFound();

        $this->assertEquals(404, http_response_code());
    }

    public function testGetRequest(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';

        $request = new class () extends Mechanic {
        }->getRequest();

        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/', $request->getPath());
    }
}
