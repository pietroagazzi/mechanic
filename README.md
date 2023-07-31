# Mechanic

[![Makefile CI](https://github.com/pietroagazzi/mechanic/actions/workflows/makefile.yml/badge.svg)](https://github.com/pietroagazzi/mechanic/actions/workflows/makefile.yml)
[![codecov](https://codecov.io/gh/pietroagazzi/mechanic/branch/main/graph/badge.svg?token=ZBZIGLRZVH)](https://codecov.io/gh/pietroagazzi/mechanic)

Mechanic is a lightweight and flexible PHP microframework created for study purposes. It has been designed to provide
developers with a profound learning experience on how PHP frameworks work and to assist them in understanding the
fundamental concepts behind routing, middleware, and HTTP request handling.

Some of Mechanic's components are inspired by those of [Symfony](https://symfony.com/) and [Laravel](https://laravel.com/).

---

```php
use Pietroagazzi\Mechanic\Http\{JsonResponse,Response};
use Pietroagazzi\Mechanic\Mechanic;

$app = new Mechanic;

$app->get('/', function () {
	return new Response('Hello World!');
});

$app->get('/users', function () {
	return new JsonResponse([
		'users' => [
			'Pietro',
			'Mario',
			'Luigi'
		]
	]);
});

$app->handle();
```

# Run it

## Using Docker :whale:

```bash
docker-compose up -d

# show logs
docker-compose logs -f

# stop containers
docker-compose down

```

:warning: The first time you run the command, it will take a while to download the images and build the containers.

:rocket: Then visit http://localhost:36000/

## Using PHP built-in server :elephant:

```bash
# install dependencies
composer install

php -S localhost:8080 -t public
```

:rocket: Then visit http://localhost:8080/
