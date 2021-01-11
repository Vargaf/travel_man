# Makefile for zinio-travel-man
# vim: set ft=make ts=8 noet

.PHONY: help
help: ## This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

# Targets
#
.PHONY: build
build: ### Builds the docker images
	if [ ! -d "./project/vendor" ]; then docker-compose -f devops/docker-compose.yml run php composer install; fi
	docker-compose -f devops/docker-compose.yml build

.PHONY: run
run: ### Runs the dockers to bring up the system
	if [ ! -d "./project/vendor" ]; then docker-compose -f devops/docker-compose.yml run php composer install; fi
	docker-compose -f devops/docker-compose.yml up

.PHONY: tests
tests: ### Runs the project tests
	if [ ! -d "./project/vendor" ]; then docker-compose -f devops/docker-compose.yml run php composer install; fi
	docker-compose -f devops/docker-compose.yml run php ./bin/phpunit Zinio

.PHONY: travel-man
travel-man: ### Runs the travel man algorithm to the cities file
	if [ ! -d "./project/vendor" ]; then docker-compose -f devops/docker-compose.yml run php composer install; fi
	docker-compose -f devops/docker-compose.yml run php symfony console zinio:travel-man $(maxNodesToForesight)
