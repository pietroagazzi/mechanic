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

$app = new Mechanic();

$app->get('/', function (): Response {
    return new Response('Hello World!');
});

$app->get('/users', function (): JsonResponse {
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
make install   # Dependencies + git hooks
make qa        # The full quality gate, exactly what CI runs
make fix       # Apply every automated fix, then re-run the checks
make help      # List every target
```

### Quality gate

`make qa` runs, in order:

| Check | Tool | Configuration |
| --- | --- | --- |
| Manifest is valid and normalized | Composer + [composer-normalize](https://github.com/ergebnis/composer-normalize) | — |
| No known vulnerable dependencies | `composer audit` + [roave/security-advisories](https://github.com/Roave/SecurityAdvisories) | — |
| Code style | [PHP-CS-Fixer](https://cs.symfony.com/) | `.php-cs-fixer.dist.php` — PSR-12 + risky rules, `strict_types` everywhere |
| Automated refactoring | [Rector](https://getrector.com/) | `rector.php` — PHP 8.5, dead code, code quality, type declarations |
| Static analysis | [PHPStan](https://phpstan.org/) | `phpstan.neon` — level `max` + [strict rules](https://github.com/phpstan/phpstan-strict-rules), no baseline |
| Tests | [PHPUnit](https://phpunit.de/) | `phpunit.xml` — fails on risky tests, warnings, deprecations and notices |

Each check also runs as its own CI job, so a failure points straight at the
category that broke.

### Git hooks

[CaptainHook](https://captainhook.info/) installs the hooks automatically on
`composer install` (`captainhook.json`):

- **commit-msg** — enforces [Conventional Commits](https://www.conventionalcommits.org/) and a 72-character subject limit
- **pre-commit** — lints staged PHP files, checks the lock file, then runs the code style and static analysis checks
- **pre-push** — runs the test suite

Bypass a hook with `git commit --no-verify` when you really need to.
