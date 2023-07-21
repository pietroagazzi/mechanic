PHPSTAN_CONFIG = ./phpstan.neon
PHPUNIT_CONFIG = ./phpunit.xml
PHPUNIT_COVERAGE_PATH = ./.phpunit.cache/coverage
PHPUNIT_CLOVER_FILE = ./.phpunit.cache/clover.xml

TEST_DIR = ./tests

GREEN=\033[0;32m
RED=\033[0;31m
NC=\033[0m

test:
	php ./vendor/bin/phpunit --configuration=$(PHPUNIT_CONFIG)

phpstan:
	php ./vendor/bin/phpstan analyze --configuration=$(PHPSTAN_CONFIG) --xdebug

coverage:
	php ./vendor/bin/phpunit --configuration=$(PHPUNIT_CONFIG) --coverage-clover=$(PHPUNIT_CLOVER_FILE)

pre-commit:
	@echo "${GREEN}Running tests...${NC}"
	@make test >/dev/null || (echo "${RED}❌ Tests failed. Aborting commit.${NC}" && exit 1)
	@echo "${GREEN}✅ Tests passed.${NC}"
	@echo "${GREEN}Running phpstan...${NC}"
	@make phpstan >/dev/null 2>/dev/null || (echo "${RED}❌ Phpstan failed. Aborting commit.${NC}" && exit 1)
	@echo "${GREEN}✅ Phpstan passed.${NC}"
	@echo "${GREEN}Running coverage...${NC}"
	@make coverage >/dev/null || (echo "${RED}❌ Coverage failed. Aborting commit.${NC}" && exit 1)
	@echo "${GREEN}✅ Coverage passed.${NC}"
	@echo "${GREEN}🚀 All checks passed. Commit away!${NC}"

.PHONY: test coverage phpstan pre-commit
