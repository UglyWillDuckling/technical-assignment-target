current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))
composer-version := 2.7

composer-install:
	@docker run --rm $(INTERACTIVE) --volume $(current-dir):/app --user $(id -u):$(id -g) \
		composer:$(composer-version) install \
		--ignore-platform-reqs \
		--no-ansi

test:
	docker exec targetadds_frontend_php ./vendor/bin/phpunit --testsuite targetadds

static-analysis:
	docker exec targetadds_frontend_php ./vendor/bin/psalm --output-format=github --shepherd

lint:
	docker exec targetadds_frontend_php ./vendor/bin/ecs check

start:
	@if [ ! -f .env.local ]; then echo '' > .env.local; fi
	docker compose up --build -d
	make clean-cache

stop:
	docker compose stop

destroy:
	docker compose down

rebuild:
	docker compose build --pull --force-rm --no-cache
	make composer-install
	make start

ping-mysql:
	@docker exec targetadds-mysql mysqladmin --user=root --password= --host "127.0.0.1" ping --silent

ping-rabbitmq:
	@docker exec targetadds-rabbitmq rabbitmqctl ping --silent

clean-cache:
	@rm -rf apps/*/*/var
	@docker exec targetadds_frontend_php ./apps/targetadds/front/bin/console cache:warmup

