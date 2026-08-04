<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic\Tests\Router;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Pietroagazzi\Mechanic\Router\Route;

#[CoversClass(Route::class)]
final class RouteTest extends TestCase
{
    public function testGetMethod(): void
    {
        $route = new Route('GET', '/', fn (): null => null);

        $this->assertSame('GET', $route->getMethod());
    }

    public function testGetPath(): void
    {
        $route = new Route('GET', '/', fn (): null => null);

        $this->assertSame('/', $route->getPath());
    }
}
