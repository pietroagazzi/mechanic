<?php

use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

return [
	Router::class => static fn() => new Router,
	Request::class => static fn() => Request::createFromGlobals(),
];