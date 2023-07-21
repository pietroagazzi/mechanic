# Mechanic

[![Makefile CI](https://github.com/pietroagazzi/mechanic/actions/workflows/makefile.yml/badge.svg)](https://github.com/pietroagazzi/mechanic/actions/workflows/makefile.yml)
[![codecov](https://codecov.io/gh/pietroagazzi/mechanic/branch/main/graph/badge.svg?token=ZBZIGLRZVH)](https://codecov.io/gh/pietroagazzi/mechanic)

🇮🇹 Mechanic ha come obiettivo quello di ricreare le funzioni principali dei framework PHP più grandi, con particolare riferimento a [Symfony](http://symfony.com/). In parole semplici, si tratta di un micro-framework che sfrutta la logica e l'implementazione dei moduli principali di Symfony per aiutare a capirlo meglio. Tieni presente che Mechanic non è pensato per essere utilizzato in produzione, ma è solo un progetto di studio creato da uno studente troppo curioso.

🇬🇧 Mechanic aims to recreate the main functions of larger PHP frameworks, with particular reference to [Symfony](http://symfony.com/). In simple terms, it is a micro-framework that leverages the logic and implementation of the main modules of Symfony to help understand it better. Note that Mechanic is not intended to be used in production, but is just a study project created by a curious student.

---

```php
$app = new Mechanic;

$app->di(function (Container $container) {
	$container->set('request', function () {
		return new Request;
	});
	$container->set('response', function () {
		return new Response;
	});
	$container->set('router', function () {
		return new Router;
	});
});

$app->middleware(function (Request $request, Closure $next) {
	$response = $next($request);
	return $response;
});

$app->group('/api', function () use ($app) {
	$app->get('/users', function () {
		return 'Hello World!';
	});
});

$app->get('/', function () {
	return 'Hello World!';
});

$app->handle();
```
