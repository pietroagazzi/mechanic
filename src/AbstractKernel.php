<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic;

use Pietroagazzi\Mechanic\DI\Container;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;
use Pietroagazzi\Mechanic\Router\Router;

/**
 * AbstractKernel contains the logic to handle HTTP requests and responses
 *
 * @see \Pietroagazzi\Mechanic\Mechanic
 *
 * @author Pietro Agazzi <contact@agazzipietro.it>
 */
abstract class AbstractKernel
{
    /**
     * Send a 404 Not Found response
     */
    public static function sendNotFound(): void
    {
        new Response('Not Found', 404)->send();
    }

    /**
     * Return a Request object from the global variables
     *
     * @see \Pietroagazzi\Mechanic\Http\Request::createFromGlobals
     */
    public function getRequest(): Request
    {
        return Request::createFromGlobals();
    }

    /**
     * Return the container used by the kernel
     */
    abstract protected function getContainer(): Container;

    /**
     * Return the router used by the kernel
     */
    abstract protected function getRouter(): Router;
}
