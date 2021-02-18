.DEFAULT_GOAL := help
.PHONY: help
include .env
export

APP_DIR=$(shell pwd)
CONFIG_DIR=$(APP_DIR)/config
DOCKER_CONFIG=$(CONFIG_DIR)/docker

help: ## This help
	@egrep -h '\s##\s' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

up: ## Launch all project's dockers
	@cd $(DOCKER_CONFIG) && docker-compose up -d
	@docker exec -uwww-data -it $(APP_NAME)_php bash -c "composer install && yarn install && yarn encore dev"

stop: ## Stop all project's running dockers
	@cd $(DOCKER_CONFIG) && docker-compose stop

down: ## Destroy every project's dockers
	@cd $(DOCKER_CONFIG) && docker-compose down

build: ## Build every project's dockers
	@cd $(DOCKER_CONFIG) && docker-compose build --build-arg APP_ENV=$(APP_ENV) --build-arg UID=$(shell id -u) --build-arg GID=$(shell id -g)

install-env: ## Install all necessary aplications on your computer
	@sudo apt-get update -y;
	@sudo apt-get upgrade -y;
	@sudo apt-get install -y git docker docker-compose; # `uidmap` is used for docker's rootless mode. For more details, go to https://docs.docker.com/engine/security/rootless/#prerequisites
	@sudo groupadd -f docker;
	@sudo usermod -aG docker $(USER);
	@echo "Log out and log back in so that your group membership is re-evaluated."
	@sudo apt-get autoremove -y;
	@sudo apt-get autoclean -y;

php: ## Access to php docker
	@docker exec -uwww-data -it $(APP_NAME)_php bash

nginx: ## Access to php docker
	@docker exec -unginx -it $(APP_NAME)_nginx bash

test: ## Execute unit test
	@docker exec -uwww-data -it $(APP_NAME)_php bash -c "php bin/phpunit"

lets-rock: build up
	@xdg-open http://localhost:8081/euromillions
	make test


