<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pietroagazzi\Mechanic\Http\Request;
use Pietroagazzi\Mechanic\Kernel;

$request = Request::createFromGlobals();

$kernel = new Kernel();
$kernel->handle($request);