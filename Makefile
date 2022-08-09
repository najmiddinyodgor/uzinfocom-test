init: docker-down docker-build docker-up migrate db-seed queue-listen

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

clear-orphans:
	docker-compose run --rm php php artisan clear:orphan-uploads

queue-listen:
	docker-compose run --rm php php artisan queue:listen -q

generate-helpers:
	docker-compose run --rm php php artisan ide-helper:generate
	docker-compose run --rm php php artisan ide-helper:models
	docker-compose run --rm php php artisan ide-helper:meta
