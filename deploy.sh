#!/usr/bin/env bash

git checkout .
git checkout master
git pull origin master --force
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod --no-debug --no-warmup
php bin/console cache:warmup --env=prod
