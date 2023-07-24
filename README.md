# Mechanic

[![Makefile CI](https://github.com/pietroagazzi/mechanic/actions/workflows/makefile.yml/badge.svg)](https://github.com/pietroagazzi/mechanic/actions/workflows/makefile.yml)
[![codecov](https://codecov.io/gh/pietroagazzi/mechanic/branch/main/graph/badge.svg?token=ZBZIGLRZVH)](https://codecov.io/gh/pietroagazzi/mechanic)

Mechanic is a lightweight and flexible PHP microframework created for study purposes. It has been designed to provide developers with a profound learning experience on how PHP frameworks work and to assist them in understanding the fundamental concepts behind routing, middleware, and HTTP request handling.

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
