up:          ## build & start
	docker compose up -d --build
down:        ## stop & delete volumes
	docker compose down -v
logs:        ## tail logs
	docker compose logs -f --tail=50
sh:          ## bash inside PHP
	docker compose exec php bash
