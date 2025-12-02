# Uptime Monitor Dashboard - Comprehensive Makefile
# Simple commands for development, testing, and deployment

.PHONY: help install setup clean run-backend run-frontend run test-backend test-frontend test build-backend build-frontend build

# Default target
.DEFAULT_GOAL := help

# Colors for output
RED := \033[0;31m
GREEN := \033[0;32m
YELLOW := \033[1;33m
BLUE := \033[0;34m
NC := \033[0m # No Color

# Project structure
BACKEND_DIR := backend
FRONTEND_DIR := frontend

# PHP Configuration
PHP := php
COMPOSER := composer

# Node.js Configuration
NPM := npm
NODE := node

# Backend Configuration
BACKEND_PORT := 8000
BACKEND_HOST := localhost

# Frontend Configuration
FRONTEND_PORT := 5173
FRONTEND_HOST := localhost

help: ## Show this help message
	@echo "$(BLUE)Uptime Monitor Dashboard - Available Commands:$(NC)"
	@echo ""
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "$(GREEN)%-20s$(NC) %s\n", $$1, $$2}'
	@echo ""
	@echo "$(YELLOW)Backend Commands:$(NC)"
	@echo "  install-backend    Install PHP dependencies"
	@echo "  run-backend       Start Laravel development server"
	@echo "  test-backend      Run PHP tests"
	@echo "  migrate-backend   Run database migrations"
	@echo "  seed-backend      Seed database with sample data"
	@echo "  clear-backend     Clear Laravel caches"
	@echo "  build-backend     Build Laravel assets"
	@echo ""
	@echo "$(YELLOW)Frontend Commands:$(NC)"
	@echo "  install-frontend  Install Node.js dependencies"
	@echo "  run-frontend      Start Vue development server"
	@echo "  build-frontend    Build Vue app for production"
	@echo "  test-frontend     Run Vue tests"
	@echo "  lint-frontend     Run ESLint"
	@echo ""
	@echo "$(YELLOW)Global Commands:$(NC)"
	@echo "  install           Install all dependencies"
	@echo "  run              Start both backend and frontend"
	@echo "  build            Build both applications"
	@echo "  test             Run all tests"
	@echo "  setup            Initial project setup"
	@echo "  clean            Clean all generated files"

# =============================================================================
# BACKEND COMMANDS (Laravel)
# =============================================================================

install-backend: ## Install PHP dependencies for backend
	@echo "$(GREEN)Installing PHP dependencies...$(NC)"
	cd $(BACKEND_DIR) && $(COMPOSER) install --no-dev --optimize-autoloader

setup-backend: ## Set up backend environment
	@echo "$(GREEN)Setting up backend...$(NC)"
	@if [ ! -f $(BACKEND_DIR)/.env ]; then \
		cp $(BACKEND_DIR)/.env.example $(BACKEND_DIR)/.env; \
		echo "$(YELLOW)Created .env file from example$(NC)"; \
	fi
	@echo "$(GREEN)Generating application key...$(NC)"
	cd $(BACKEND_DIR) && $(PHP) artisan key:generate --force
	@echo "$(GREEN)Running migrations...$(NC)"
	cd $(BACKEND_DIR) && $(PHP) artisan migrate --force
	@echo "$(GREEN)Caching configuration...$(NC)"
	cd $(BACKEND_DIR) && $(PHP) artisan config:cache
	@echo "$(GREEN)Optimizing autoloader...$(NC)"
	cd $(BACKEND_DIR) && $(COMPOSER) dump-autoload --optimize --classmap-authoritative

run-backend: ## Start Laravel development server
	@echo "$(GREEN)Starting Laravel development server on http://$(BACKEND_HOST):$(BACKEND_PORT)$(NC)"
	cd $(BACKEND_DIR) && $(PHP) artisan serve --host=$(BACKEND_HOST) --port=$(BACKEND_PORT)

test-backend: ## Run PHP tests
	@echo "$(GREEN)Running PHP tests...$(NC)"
	cd $(BACKEND_DIR) && $(PHP) artisan test

migrate-backend: ## Run database migrations
	@echo "$(GREEN)Running database migrations...$(NC)"
	cd $(BACKEND_DIR) && $(PHP) artisan migrate --force

seed-backend: ## Seed database with sample data
	@echo "$(GREEN)Seeding database...$(NC)"
	cd $(BACKEND_DIR) && $(PHP) artisan db:seed --force

clear-backend: ## Clear Laravel caches
	@echo "$(GREEN)Clearing Laravel caches...$(NC)"
	cd $(BACKEND_DIR) && $(PHP) artisan cache:clear
	cd $(BACKEND_DIR) && $(PHP) artisan config:clear
	cd $(BACKEND_DIR) && $(PHP) artisan route:clear
	cd $(BACKEND_DIR) && $(PHP) artisan view:clear

build-backend: ## Build Laravel assets (Vite)
	@echo "$(GREEN)Building Laravel assets...$(NC)"
	cd $(BACKEND_DIR) && $(NPM) run build

serve-backend: ## Serve built Laravel application
	@echo "$(GREEN)Serving built Laravel application...$(NC)"
	cd $(BACKEND_DIR) && $(PHP) artisan serve --host=$(BACKEND_HOST) --port=$(BACKEND_PORT)

# =============================================================================
# FRONTEND COMMANDS (Vue)
# =============================================================================

install-frontend: ## Install Node.js dependencies for frontend
	@echo "$(GREEN)Installing Node.js dependencies...$(NC)"
	cd $(FRONTEND_DIR) && $(NPM) install

setup-frontend: ## Set up frontend environment
	@echo "$(GREEN)Setting up frontend...$(NC)"
	@echo "$(GREEN)Installing dependencies...$(NC)"
	cd $(FRONTEND_DIR) && $(NPM) install
	@echo "$(GREEN)Building for development...$(NC)"
	cd $(FRONTEND_DIR) && $(NPM) run build

run-frontend: ## Start Vue development server
	@echo "$(GREEN)Starting Vue development server on http://$(FRONTEND_HOST):$(FRONTEND_PORT)$(NC)"
	cd $(FRONTEND_DIR) && $(NPM) run dev -- --host $(FRONTEND_HOST) --port $(FRONTEND_PORT)

build-frontend: ## Build Vue app for production
	@echo "$(GREEN)Building Vue app for production...$(NC)"
	cd $(FRONTEND_DIR) && $(NPM) run build

test-frontend: ## Run Vue tests
	@echo "$(GREEN)Running Vue tests...$(NC)"
	cd $(FRONTEND_DIR) && $(NPM) run test

lint-frontend: ## Run ESLint
	@echo "$(GREEN)Running ESLint...$(NC)"
	cd $(FRONTEND_DIR) && $(NPM) run lint

preview-frontend: ## Preview built Vue app
	@echo "$(GREEN)Previewing built Vue app...$(NC)"
	cd $(FRONTEND_DIR) && $(NPM) run preview -- --port $(FRONTEND_PORT) --host $(FRONTEND_HOST)

# =============================================================================
# GLOBAL COMMANDS
# =============================================================================

install: ## Install all dependencies (backend and frontend)
	@echo "$(GREEN)Installing all dependencies...$(NC)"
	@$(MAKE) install-backend
	@$(MAKE) install-frontend

setup: ## Initial project setup
	@echo "$(GREEN)Setting up Uptime Monitor Dashboard...$(NC)"
	@$(MAKE) install
	@$(MAKE) setup-backend
	@$(MAKE) setup-frontend
	@echo "$(GREEN)Setup complete!$(NC)"
	@echo "$(YELLOW)Next steps:$(NC)"
	@echo "  1. Configure your database in backend/.env"
	@echo "  2. Run 'make run' to start both servers"

run: ## Start both backend and frontend servers
	@echo "$(GREEN)Starting development environment...$(NC)"
	@echo "$(YELLOW)Backend will run on http://$(BACKEND_HOST):$(BACKEND_PORT)$(NC)"
	@echo "$(YELLOW)Frontend will run on http://$(FRONTEND_HOST):$(FRONTEND_PORT)$(NC)"
	@echo "$(YELLOW)Press Ctrl+C to stop all servers$(NC)"
	@$(MAKE) -j2 run-backend run-frontend

build: ## Build both applications for production
	@echo "$(GREEN)Building for production...$(NC)"
	@$(MAKE) build-backend
	@$(MAKE) build-frontend
	@echo "$(GREEN)Build complete!$(NC)"

test: ## Run all tests
	@echo "$(GREEN)Running all tests...$(NC)"
	@$(MAKE) test-backend
	@$(MAKE) test-frontend

clean: ## Clean all generated files and caches
	@echo "$(GREEN)Cleaning project...$(NC)"
	@$(MAKE) clear-backend
	@echo "Cleaning Node modules..."
	@rm -rf $(BACKEND_DIR)/node_modules
	@rm -rf $(FRONTEND_DIR)/node_modules
	@echo "Cleaning build artifacts..."
	@rm -rf $(BACKEND_DIR)/public/build
	@rm -rf $(FRONTEND_DIR)/dist
	@echo "Cleaning logs..."
	@rm -rf $(BACKEND_DIR)/storage/logs/*.log
	@echo "Cleaning composer cache..."
	@$(COMPOSER) clear-cache
	@echo "$(GREEN)Clean complete!$(NC)"

dev: ## Full development environment setup
	@echo "$(GREEN)Setting up development environment...$(NC)"
	@$(MAKE) clean
	@$(MAKE) install
	@$(MAKE) setup
	@echo "$(GREEN)Development environment ready!$(NC)"
	@echo "$(YELLOW)Run 'make run' to start development servers$(NC)"

# =============================================================================
# MAINTENANCE COMMANDS
# =============================================================================

status: ## Show project status
	@echo "$(BLUE)Project Status:$(NC)"
	@echo "Backend directory: $(BACKEND_DIR)"
	@echo "Frontend directory: $(FRONTEND_DIR)"
	@echo ""
	@echo "$(YELLOW)Backend status:$(NC)"
	@if [ -f $(BACKEND_DIR)/.env ]; then \
		echo "$(GREEN)✓$(NC) .env file exists"; \
	else \
		echo "$(RED)✗$(NC) .env file missing"; \
	fi
	@if [ -d $(BACKEND_DIR)/vendor ]; then \
		echo "$(GREEN)✓$(NC) PHP dependencies installed"; \
	else \
		echo "$(RED)✗$(NC) PHP dependencies not installed"; \
	fi
	@echo ""
	@echo "$(YELLOW)Frontend status:$(NC)"
	@if [ -d $(FRONTEND_DIR)/node_modules ]; then \
		echo "$(GREEN)✓$(NC) Node.js dependencies installed"; \
	else \
		echo "$(RED)✗$(NC) Node.js dependencies not installed"; \
	fi
	@if [ -d $(FRONTEND_DIR)/dist ]; then \
		echo "$(GREEN)✓$(NC) Frontend built"; \
	else \
		echo "$(YELLOW)○$(NC) Frontend not built yet"; \
	fi

logs: ## Show Laravel logs
	@echo "$(GREEN)Laravel logs (last 50 lines):$(NC)"
	@tail -n 50 $(BACKEND_DIR)/storage/logs/laravel.log 2>/dev/null || echo "No log file found"

watch: ## Watch for changes and rebuild
	@echo "$(GREEN)Starting watch mode...$(NC)"
	@$(MAKE) -j2 run-frontend WATCH=true run-backend WATCH=true

# =============================================================================
# DATABASE COMMANDS
# =============================================================================

db-reset: ## Reset database and run fresh migrations
	@echo "$(RED)WARNING: This will delete all data!$(NC)"
	@read -p "Are you sure? [y/N]: " confirm && [ "$$confirm" = "y" ]
	@$(MAKE) clear-backend
	cd $(BACKEND_DIR) && $(PHP) artisan migrate:fresh --seed
	@echo "$(GREEN)Database reset complete!$(NC)"

db-backup: ## Create database backup
	@echo "$(GREEN)Creating database backup...$(NC)"
	@mkdir -p backups
	@mysqldump -u $$(grep DB_USERNAME $(BACKEND_DIR)/.env | cut -d'=' -f2) -p$$(grep DB_PASSWORD $(BACKEND_DIR)/.env | cut -d'=' -f2) $$(grep DB_DATABASE $(BACKEND_DIR)/.env | cut -d'=' -f2) > backups/backup-$$(date +%Y%m%d_%H%M%S).sql
	@echo "$(GREEN)Database backup created in backups/ directory$(NC)"

# =============================================================================
# SECURITY COMMANDS
# =============================================================================

secure: ## Apply security optimizations
	@echo "$(GREEN)Applying security optimizations...$(NC)"
	cd $(BACKEND_DIR) && $(PHP) artisan key:generate --force
	cd $(BACKEND_DIR) && $(PHP) artisan config:cache
	cd $(BACKEND_DIR) && $(PHP) artisan route:cache
	@echo "$(GREEN)Security optimizations applied!$(NC)"

# =============================================================================
# CONTAINER COMMANDS (if Docker is available)
# =============================================================================

docker-up: ## Start with Docker Compose
	@if command -v docker-compose >/dev/null 2>&1; then \
		docker-compose up -d; \
		echo "$(GREEN)Docker containers started!$(NC)"; \
	elif command -v docker >/dev/null 2>&1; then \
		docker compose up -d; \
		echo "$(GREEN)Docker containers started!$(NC)"; \
	else \
		echo "$(RED)Docker not found!$(NC)"; \
		exit 1; \
	fi

docker-down: ## Stop Docker Compose
	@if command -v docker-compose >/dev/null 2>&1; then \
		docker-compose down; \
	elif command -v docker >/dev/null 2>&1; then \
		docker compose down; \
	else \
		echo "$(RED)Docker not found!$(NC)"; \
		exit 1; \
	fi

docker-logs: ## Show Docker logs
	@if command -v docker-compose >/dev/null 2>&1; then \
		docker-compose logs -f; \
	elif command -v docker >/dev/null 2>&1; then \
		docker compose logs -f; \
	else \
		echo "$(RED)Docker not found!$(NC)"; \
		exit 1; \
	fi
