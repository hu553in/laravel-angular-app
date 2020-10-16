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

.PHONY: reconfigure
reconfigure:
	docker-compose exec api sh -c \
	'php artisan config:clear && php artisan cache:clear && php artisan config:cache'

.PHONY: prepareDatabase
prepareDatabase:
	docker-compose exec api sh -c 'php artisan migrate:fresh --seed'

.PHONY: runTestsBackend
runTestsBackend:
	docker-compose exec api sh -c 'php artisan test'

.PHONY: runTestsFrontendCommon
runTestsFrontendCommon:
	docker-compose exec ui sh -c 'npx ng test'

.PHONY: runTestsFrontendEndToEnd
runTestsFrontendEndToEnd:
	docker-compose exec ui sh -c 'npx ng e2e --port 4300'
