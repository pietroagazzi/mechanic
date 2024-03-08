<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pietroagazzi\Mechanic\DI\Container;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;
use Pietroagazzi\Mechanic\Mechanic;

$container = new Container();

$container
	->set(Request::class, function () {
		return Request::createFromGlobals();
	});

$app = new Mechanic($container);

$app->get('/welcome', function (Request $request) {
	$name = $request->getQuery()['name'] ?? 'World';

	return new Response("Hello, $name!");
});

$app->get('/', function () {
	return new Response('', 302, ['Location' => '/welcome']);
});

$app->handle();