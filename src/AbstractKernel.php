<?php

namespace Pietroagazzi\Mechanic;

/**
 * AbstractKernel contains the logic to handle HTTP requests and responses
 *
 * @see \Pietroagazzi\Mechanic\Mechanic
 * @author Pietro Agazzi <contact@agazzipietro.it>
 */
abstract class AbstractKernel
{
	/**
	 * Send a 404 Not Found response
	 */
	public static function sendNotFound(): void
	{
		(new Http\Response('Not Found', 404))->send();
	}

	/**
	 * Return a Request object from the global variables
	 *
	 * @see \Pietroagazzi\Mechanic\Http\Request::createFromGlobals
	 */
	public function getRequest(): Http\Request
	{
		return Http\Request::createFromGlobals();
	}

	/**
	 * Return the container used by the kernel
	 */
	abstract protected function getContainer(): DI\Container;

	/**
	 * Return the router used by the kernel
	 */
	abstract protected function getRouter(): Router\Router;
}