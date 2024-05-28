#!/usr/bin/env sh

# create the containers
docker compose up -d --force-recreate --remove-orphans --build

# install the vendors
docker compose exec server composer install

# create the .env.local.php file
docker compose exec server php bin/console dotenv:dump dev

# load migrations
docker compose exec server php bin/console doctrine:migrations:migrate

# load the fixtures
docker compose exec server php bin/console doctrine:fixtures:load

exit 0