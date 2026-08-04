<?php

declare(strict_types=1);

namespace Pietroagazzi\Mechanic;

use Pietroagazzi\Mechanic\DI\Container;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;
use Pietroagazzi\Mechanic\Router\Route;
use Pietroagazzi\Mechanic\Router\Router;
use ReflectionException;
use RuntimeException;

/**
 * Mechanic is the kernel of the framework.
 * It handles the request and the response.
 *
 * Secondary kernel should extend this class (e.g. ApiKernel)
 *
 * @author Pietro Agazzi <contact@agazzipietro.it>
 */
class Mechanic extends AbstractKernel
{
    public function __construct(
        protected Container $container = new Container(),
        protected Router $router = new Router(),
    ) {
    }

    /**
     * Add a route to the router with the GET method
     *
     * @see \Pietroagazzi\Mechanic\Router\Router::addRoute
     */
    public function get(string $path, callable $handler): void
    {
        $this->router->addRoute(new Route('GET', $path, $handler(...)));
    }

    /**
     * Add a route to the router with the POST method
     *
     * @see \Pietroagazzi\Mechanic\Router\Router::addRoute
     */
    public function post(string $path, callable $handler): void
    {
        $this->router->addRoute(new Route('POST', $path, $handler(...)));
    }

    /**
     * Add a route to the router with the PUT method
     *
     * @see \Pietroagazzi\Mechanic\Router\Router::addRoute
     */
    public function put(string $path, callable $handler): void
    {
        $this->router->addRoute(new Route('PUT', $path, $handler(...)));
    }

    /**
     * Handle the request and send the response.
     * This method is the entry point of the framework, it should be called only once per request.
     * It will try to match the request to a route and invoke the handler.
     *
     * - If no request is provided, the method will try to create one from the global variables.
     * - If no route is found, a 404 response will be sent.
     *
     * @param Request|null $request the request object to handle
     *
     * @throws ReflectionException if the handler is not callable
     * @throws RuntimeException if the handler does not return a Response
     */
    public function handle(?Request $request = null): void
    {
        $request ??= $this->getRequest();

        $route = $this->router->match($request->getMethod(), $request->getPath());

        // If no route is found, send a 404 response
        if (!$route instanceof Route) {
            self::sendNotFound();
            return;
        }

        $response = $this->container->invoke($route->getHandler());

        if (!$response instanceof Response) {
            throw new RuntimeException(sprintf(
                'The handler for "%s %s" must return a %s, got %s',
                $route->getMethod(),
                $route->getPath(),
                Response::class,
                get_debug_type($response),
            ));
        }

        $response->send();
    }

    protected function getContainer(): Container
    {
        return $this->container;
    }

    protected function getRouter(): Router
    {
        return $this->router;
    }
}
