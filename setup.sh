#!/bin/sh
composer install
composer require maatwebsite/excel vladimir-yuldashev/laravel-queue-rabbitmq predis/predis --with-all-dependencies
php artisan migrate
php artisan key:generate
echo "Ready! System is configured."