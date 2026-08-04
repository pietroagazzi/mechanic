<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic\Router;

use Closure;

readonly class Route
{
    /**
     * The handler is invoked through the container, which autowires its parameters,
     * so its signature is intentionally left open.
     */
    public function __construct(
        private string $method,
        private string $path,
        private Closure $handler,
    ) {
    }

    public function getHandler(): Closure
    {
        return $this->handler;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
