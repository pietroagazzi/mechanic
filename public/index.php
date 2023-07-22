<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pietroagazzi\Mechanic\DI\Container;
use Pietroagazzi\Mechanic\Http\JsonResponse;
use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Http\Response;
use Pietroagazzi\Mechanic\Mechanic;

$container = new Container();

$container
	->set(Request::class, function () {
		return Request::createFromGlobals();
	});

$app = new Mechanic($container);

$app->get('/home', function (Request $request) {
	return new Response($request->getQuery()['name'] ?? 'Hello World');
});

$app->get('/users', function (Request $request) {
	return new JsonResponse($request->getBody());
});

$app->post('/post', function () {
	return new Response('POST request');
});

$app->put('/put', function () {
	return new Response('PUT request');
});


$app->handle();