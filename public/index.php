<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pietroagazzi\Mechanic\Http\Response;
use Pietroagazzi\Mechanic\Mechanic;

$app = new Mechanic;

$app->get('/home', function () {
	return new Response('Hello World!');
});

$app->handle();