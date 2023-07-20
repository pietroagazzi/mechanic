PHPSTAN_CONFIG = ./phpstan.neon
PHPUNIT_CONFIG = ./phpunit.xml
PHPUNIT_COVERAGE_PATH = ./.phpunit.cache/coverage
PHPUNIT_CLOVER_FILE = ./.phpunit.cache/clover.xml

TEST_DIR = ./tests

test:
	php ./vendor/bin/phpunit --configuration=$(PHPUNIT_CONFIG)

phpstan:
	php ./vendor/bin/phpstan analyze --configuration=$(PHPSTAN_CONFIG) --xdebug

coverage:
	php ./vendor/bin/phpunit --configuration=$(PHPUNIT_CONFIG) --coverage-clover=$(PHPUNIT_CLOVER_FILE)

pre-commit:
	make test
	make phpstan
	make coverage

.PHONY: test coverage phpstan pre-commit
