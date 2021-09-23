#!/bin/bash

git pull
composer install --optimize-autoloader
./bin/console cache:warmup -e prod
chown -R www-data:www-data var
./bin/console d:m:m -n