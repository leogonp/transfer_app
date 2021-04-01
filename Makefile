run :
	docker-compose up --build
clean:
	docker-compose down
test:
	docker exec -it transfer_app_php_1 vendor/phpunit/phpunit/phpunit