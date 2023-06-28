#!/bin/bash

docker-compose build --no-cache &&
docker-compose up -d &&
docker-compose exec laravel.test composer install &&
./vendor/bin/sail up -d
