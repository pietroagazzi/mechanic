<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pietroagazzi\Mechanic\Http\JsonResponse;
use Pietroagazzi\Mechanic\Http\Response;
use Pietroagazzi\Mechanic\Mechanic;

$app = new Mechanic;

$app->get('/home', function () {
	return new Response('Hello World!');
});

$app->get('/users', function () {
	return new JsonResponse([
		'users' => [
			[
				'name' => 'Pietro',
				'age' => 20,
			],
			[
				'name' => 'John',
				'age' => 30,
			],
		],
	]);
});

$app->post('/post', function () {
	return new Response('POST request');
});

$app->put('/put', function () {
	return new Response('PUT request');
});


$app->handle();