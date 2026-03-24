docker-up:
	@echo "Up All Services"
	docker compose up

docker-down:
	@echo "Down docker-compose"
	docker compose down

docker-clear-all:
	@echo "Warning !!!! Delete ALL volumes, containers and images"
	docker volume prune
	docker system prune -a

test:
	@echo "Execute Testing"
	docker exec -ti gridcp-proxmox-client bash -c "php ./vendor/bin/phpunit --verbose --configuration phpunit.dist.xml"

bash:
	docker exec -it gridcp-proxmox-client bash
