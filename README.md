# Mechanic

[![CI](https://github.com/pietroagazzi/mechanic/actions/workflows/ci.yml/badge.svg)](https://github.com/pietroagazzi/mechanic/actions/workflows/ci.yml)
[![codecov](https://codecov.io/gh/pietroagazzi/mechanic/branch/main/graph/badge.svg?token=ZBZIGLRZVH)](https://codecov.io/gh/pietroagazzi/mechanic)

Mechanic is a lightweight and flexible PHP microframework created for study purposes. It has been designed to provide
developers with a profound learning experience on how PHP frameworks work and to assist them in understanding the
fundamental concepts behind routing, middleware, and HTTP request handling.

Some of Mechanic's components are inspired by those of [Symfony](https://symfony.com/) and [Laravel](https://laravel.com/).

---

## Usage

Mechanic is a library: it ships the framework only, not a front controller. Create your own `public/index.php`:

```php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Pietroagazzi\Mechanic\Http\{JsonResponse, Response};
use Pietroagazzi\Mechanic\Mechanic;

$app = new Mechanic;

$app->get('/', function () {
	return new Response('Hello World!');
});

$app->get('/users', function () {
	return new JsonResponse([
		'users' => ['Pietro', 'Mario', 'Luigi'],
	]);
});

$app->handle();
```

Then serve it with the PHP built-in server:

```bash
composer install
php -S localhost:8080 -t public
```

:rocket: Then visit http://localhost:8080/

## Development

```bash
make test      # PHPUnit
make coverage  # PHPUnit with coverage (needs pcov or xdebug)
make phpstan   # Static analysis
```
