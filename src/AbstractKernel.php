<?php

namespace Pietroagazzi\Mechanic;

abstract class AbstractKernel
{
	public static function sendNotFound(): void
	{
		(new Http\Response('Not Found', 404))->send();
	}

	abstract protected function getContainer(): DI\Container;

	abstract protected function getRouter(): Router\Router;

	public function getRequest(): Http\Request
	{
		return Http\Request::createFromGlobals();
	}
}