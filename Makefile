include .env

docker-install:
	@if [ -z "$$(docker network ls | grep coursework-external)" ]; then \
		echo "Сеть coursework-external не найдена. Создаем..."; \
		docker network create -d bridge coursework-external; \
	else \
		echo "Сеть coursework-external уже существует. Пропускаем создание..."; \
	fi; \
	docker context use rootless
	docker compose -f ./docker-compose.yml up --build -d --force-recreate --remove-orphans

docker-stop:
	docker compose down

dphp:
	@docker exec -it coursework-php-container $(cmd)

up:
	@docker compose -f docker-compose.yml up -d

down:
	@docker compose -f docker-compose.yml down --remove-orphans

restart: down up