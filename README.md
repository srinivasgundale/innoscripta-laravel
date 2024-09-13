

## Project Setup

- clone repo https://github.com/srinivasgundale/innoscripta-laravel.git
- cd innoscripta-laravel
- install Docker (Ignore if already installed)
- Make sure add .env to project root folder
- docker-compose up --build

- docker-compose exec app php artisan config:clear
- docker-compose exec app php artisan config:cahe
- docker-compose exec app php artisan migrate
- docker-compose exec app php artisan passport:install
- docker-compose exec chmod -R 777 storage bootstrap/cache
- access the laravel-app at http://localhost:8000/ (change port number in docker compose if needed)
- access phpmyadmin at http://localhost:8001/ (change port number in docker compose if needed)
