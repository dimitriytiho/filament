up:
	docker compose up -d
stop:
	docker compose stop
down:
	docker compose down --remove-orphans
build:
	docker compose build
destroy:
	docker compose down --remove-orphans
remake:
	@make destroy
	@make install
install:
	@make build
	@make up
ps:
	docker compose ps
logs:
	docker compose logs
mysql:
	docker compose exec mysql bash
tests:
	docker compose exec app bash -c 'composer run tests'
dump:
	docker compose exec mysql bash -c 'cd scripts && sh export.sh'


composer:
	docker compose exec app composer update
migrate:
	docker compose exec app php artisan migrate
npm:
	docker compose exec app npm install
vite:
	docker compose exec app npm run build
