<?php

namespace Pietroagazzi\Mechanic;

abstract class AbstractKernel
{
	public static function sendNotFound(): void
	{
		(new Http\Response('Not Found', 404))->send();
	}

	public function getRequest(): Http\Request
	{
		return Http\Request::createFromGlobals();
	}
}