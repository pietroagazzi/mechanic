# PHPUnit
PHPUNIT_CONFIG = ./phpunit.xml

# Phpstan
PHPSTAN_CONFIG = ./phpstan.neon

# Coverage threshold
COVERAGE_THRESHOLD = 80

GREEN=\033[0;32m
RED=\033[0;31m
NC=\033[0m

test:
	php ./vendor/bin/phpunit --configuration=$(PHPUNIT_CONFIG) --coverage-text --colors=never

phpstan:
	php ./vendor/bin/phpstan analyze --configuration=$(PHPSTAN_CONFIG) --xdebug


pre-commit:
	@echo "${GREEN}Running pre-commit checks...${NC}"
	@make phpstan >/dev/null 2>/dev/null || (echo "${RED}❌ Phpstan failed. Aborting commit.${NC}" && exit 1)
	@echo "${GREEN}✅ Phpstan passed.${NC}"
	@make test >/dev/null 2>/dev/null || (echo "${RED}❌ Tests failed. Aborting commit.${NC}" && exit 1)
	@echo "${GREEN}✅ Tests passed.${NC}"
	@echo "${GREEN}🚀 Pre-commit checks passed.${NC}"


.PHONY: test coverage phpstan pre-commit
