init: docker-down docker-build docker-up migrate db-seed

docker-build:
	docker-compose build

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

composer-install:
	docker-compose run --rm php composer install

migrate:
	docker-compose run --rm php php artisan migrate

db-seed:
	docker-compose run --rm php php artisan db:seed

test:
	docker-compose run --rm php php artisan test

generate-helpers:
	docker-compose run --rm php php artisan ide-helper:generate
	docker-compose run --rm php php artisan ide-helper:models
	docker-compose run --rm php php artisan ide-helper:meta