#!/usr/bin/env bash

git checkout .
git checkout master
git pull origin master --force
composer install --no-dev --optimize-autoloader
php bin/console cache:clear --env=prod --no-debug
#php bin/console assetic:dump --env=prod --no-debug
php bin/console doctrine:schema:update --force
