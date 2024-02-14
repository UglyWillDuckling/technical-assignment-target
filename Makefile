current-dir := $(dir $(abspath $(lastword $(MAKEFILE_LIST))))

composer-install:
	@docker run --rm $(INTERACTIVE) --volume $(current-dir):/app --user $(id -u):$(id -g) \
		composer:2.* install \
			--ignore-platform-reqs \
			--no-ansi

test:
	docker exec codely-php_ddd_skeleton-backoffice_backend-php ./vendor/bin/phpunit --testsuite backoffice

static-analysis:
	docker exec codely-php_ddd_skeleton-mooc_backend-php ./vendor/bin/psalm --output-format=github --shepherd

lint:
	docker exec codely-php_ddd_skeleton-mooc_backend-php ./vendor/bin/ecs check

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
	@docker exec codely-php_ddd_skeleton-mooc-mysql mysqladmin --user=root --password= --host "127.0.0.1" ping --silent

ping-elasticsearch:
	@curl -I -XHEAD localhost:9200

ping-rabbitmq:
	@docker exec codely-php_ddd_skeleton-rabbitmq rabbitmqctl ping --silent

clean-cache:
	@rm -rf apps/*/*/var
	@docker exec codely-php_ddd_skeleton-mooc_backend-php ./apps/mooc/backend/bin/console cache:warmup
