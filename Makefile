COMPOSER ?= composer

GREEN = \033[0;32m
YELLOW = \033[0;33m
NC = \033[0m

.DEFAULT_GOAL := help

help: ## Show this help
	@grep -hE '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) \
		| awk 'BEGIN {FS = ":.*?## "}; {printf "$(GREEN)%-14s$(NC) %s\n", $$1, $$2}'

install: ## Install dependencies and git hooks
	$(COMPOSER) install

qa: ## Run the full quality gate (same checks as CI)
	@printf "$(YELLOW)Running the quality gate...$(NC)\n"
	@$(COMPOSER) qa

cs: ## Fix code style
	@$(COMPOSER) cs

cs-check: ## Check code style without modifying files
	@$(COMPOSER) cs:check

rector: ## Apply Rector refactorings
	@$(COMPOSER) rector

rector-check: ## Report Rector refactorings without applying them
	@$(COMPOSER) rector:check

stan: ## Run static analysis
	@$(COMPOSER) stan

test: ## Run the test suite
	@$(COMPOSER) test

coverage: ## Run the test suite with coverage (requires pcov or xdebug)
	@$(COMPOSER) test:coverage

audit: ## Check dependencies for known vulnerabilities
	@$(COMPOSER) audit

fix: ## Apply every automated fix, then re-run the checks
	@$(COMPOSER) cs
	@$(COMPOSER) rector
	@$(COMPOSER) normalize
	@$(MAKE) stan test

clean: ## Remove build artifacts and tool caches
	rm -rf .build

.PHONY: help install qa cs cs-check rector rector-check stan test coverage audit fix clean
