.PHONY: help install start stop restart build logs shell composer test coverage clean check-ports validate watch

# Colors for terminal output
COLOR_RESET = \033[0m
COLOR_INFO = \033[32m
COLOR_COMMENT = \033[33m

# Add this at the top of your Makefile, after the color definitions
include infrastructure/.env.docker

## Display help message
help:
	@echo "${COLOR_INFO}Symfony + Vue.js Development Commands${COLOR_RESET}"
	@echo "${COLOR_COMMENT}Usage:${COLOR_RESET}"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "  \033[32m%-15s\033[0m %s\n", $$1, $$2}'

# Add this before the install target
generate-certs: ## Generate SSL certificates
	@echo "${COLOR_INFO}Generating SSL certificates...${COLOR_RESET}"
	@chmod +x infrastructure/ssl/generate-certs.sh
	@./infrastructure/ssl/generate-certs.sh

# Update the install target to include certificate generation
install: ## Install project dependencies
	@echo "${COLOR_INFO}Creating project structure...${COLOR_RESET}"
	@if [ ! -f "composer.json" ]; then \
		echo "${COLOR_INFO}Initializing Symfony project...${COLOR_RESET}"; \
		docker run --rm -v $(PWD):/app -w /app composer create-project symfony/skeleton . --no-interaction; \
		docker run --rm -v $(PWD):/app -w /app composer require symfony/webpack-encore-bundle; \
	fi
	@echo "${COLOR_INFO}Generating SSL certificates...${COLOR_RESET}"
	$(MAKE) generate-certs
	@echo "${COLOR_INFO}Starting containers...${COLOR_RESET}"
	docker-compose up -d
	@echo "${COLOR_INFO}Installing PHP dependencies...${COLOR_RESET}"
	$(MAKE) composer CMD="install"
	@echo "${COLOR_INFO}Installing Node dependencies...${COLOR_RESET}"
	docker-compose exec vehiclefeecalculator npm install
	@echo "${COLOR_INFO}Project installed successfully!${COLOR_RESET}"

start: ## Start the development environment and all services
	@echo "${COLOR_INFO}Starting development environment...${COLOR_RESET}"
	@if [ "${FORCE}" != "true" ]; then \
		$(MAKE) check-ports; \
	fi
	@echo "${COLOR_INFO}Starting Docker services...${COLOR_RESET}"
	docker-compose up -d
	docker-compose exec vehiclefeecalculator npm run dev
	@echo "${COLOR_INFO}Development environment is ready!${COLOR_RESET}"
	@echo "${COLOR_COMMENT}Application: https://localhost:${APP_PORT}${COLOR_RESET}"
	@echo "${COLOR_INFO}View logs with: make logs${COLOR_RESET}"
	

stop: ## Stop the development environment and cleanup
	@echo "${COLOR_INFO}Stopping development environment...${COLOR_RESET}"
	docker-compose down --remove-orphans
	@echo "${COLOR_INFO}Development environment stopped${COLOR_RESET}"

restart: ## Restart the development environment
	@echo "${COLOR_INFO}Restarting development environment...${COLOR_RESET}"
	$(MAKE) stop
	$(MAKE) start

build: ## Rebuild all containers
	docker-compose build --no-cache
	$(MAKE) install

logs: ## View logs from all containers
	docker-compose logs -f

shell: ## Access the PHP container shell
	docker-compose exec vehiclefeecalculator bash

composer: ## Run Composer commands (usage: make composer CMD="command")
	@if [ -z "$(CMD)" ]; then \
		echo "${COLOR_COMMENT}Usage: make composer CMD=\"command\"${COLOR_RESET}"; \
		exit 1; \
	fi
	docker-compose exec vehiclefeecalculator composer $(CMD)

test: ## Run PHPUnit tests
	docker-compose exec vehiclefeecalculator php bin/phpunit

cache-clear: ## Clear Symfony cache
	docker-compose exec vehiclefeecalculator php bin/console cache:clear

watch: ## Watch assets for changes
	@echo "${COLOR_INFO}Watching assets for changes...${COLOR_RESET}"
	docker-compose exec vehiclefeecalculator npm run watch

clean: ## Remove all containers, volumes, generated files, and dependencies
	@echo "${COLOR_INFO}Cleaning up everything...${COLOR_RESET}"
	@echo "${COLOR_INFO}Stopping and removing containers...${COLOR_RESET}"
	docker-compose down -v --remove-orphans
	@echo "${COLOR_INFO}Removing node_modules...${COLOR_RESET}"
	rm -rf node_modules
	@echo "${COLOR_INFO}Removing vendor directory...${COLOR_RESET}"
	rm -rf vendor
	@echo "${COLOR_INFO}Removing SSL certificates...${COLOR_RESET}"
	rm -rf infrastructure/ssl/*.pem
	@echo "${COLOR_INFO}Removing Symfony cache...${COLOR_RESET}"
	rm -rf var/cache/*
	@echo "${COLOR_INFO}Removing built assets...${COLOR_RESET}"
	rm -rf public/build/*
	@echo "${COLOR_INFO}Project cleaned successfully!${COLOR_RESET}"