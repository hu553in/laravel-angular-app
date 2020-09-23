.PHONY: run
run:
	docker-compose up --build

.PHONY: createDotEnvForDocker
createDotEnvForDocker:
	cp ./.env.example ./.env
	sed -i 's/DB_HOST=127.0.0.1/DB_HOST=db/g' ./.env
	sed -i 's/DB_DATABASE=laravel/DB_DATABASE=laravel_angular_app/g' ./.env
	sed -i 's/DB_USERNAME=root/DB_USERNAME=laravel_angular_app_username/g' ./.env
	sed -i 's/DB_PASSWORD=/DB_PASSWORD=laravel_angular_app_password/g' ./.env

.PHONY: generateSecrets
generateSecrets:
	docker-compose exec api sh -c \
	'php artisan key:generate && php artisan jwt:secret -f'

.PHONY: prepareDatabase
prepareDatabase:
	docker-compose exec api sh -c \
	'php artisan migrate:fresh --seed'
