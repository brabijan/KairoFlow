.PHONY: help up down build install migrate test phpstan cs cs-fix clean

help: ## Show this help message
	@echo "Usage: make [target]"
	@echo ""
	@echo "Available targets:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "  %-15s %s\n", $$1, $$2}'

up: ## Start Docker containers
	docker-compose up -d

down: ## Stop Docker containers
	docker-compose down

build: ## Build Docker containers
	docker-compose build

install: ## Install Composer dependencies
	docker-compose exec app composer install

migrate: ## Run database migrations
	docker-compose exec app bin/console migrations:migrate

test: ## Run tests
	docker-compose exec app composer test

phpstan: ## Run PHPStan analysis
	docker-compose exec app composer phpstan

cs: ## Check code style
	docker-compose exec app composer cs

cs-fix: ## Fix code style
	docker-compose exec app composer cs-fix

clean: ## Clean temporary files
	rm -rf temp/* log/*
	mkdir -p temp log
	touch temp/.gitkeep log/.gitkeep