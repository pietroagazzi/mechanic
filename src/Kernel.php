<?php

namespace Pietroagazzi\Mechanic;

use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;

class Kernel
{
	public function handle(Request $request): void
	{
		$response = new Response();
		$response->setContent($request->getMethod());
		$response->send();
	}
}