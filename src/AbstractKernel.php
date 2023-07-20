<?php

namespace Pietroagazzi\Mechanic;

abstract class AbstractKernel
{
	public static function sendNotFound(): void
	{
		(new Http\Response(status: 404))->send();
	}

	public function getRequest(): Http\Request
	{
		return Http\Request::createFromGlobals();
	}
}