#!/usr/bin/env sh

# create the test database
docker compose exec server php bin/console doctrine:database:drop --env=test --force
docker compose exec server php bin/console doctrine:database:create --env=test
docker compose exec server php bin/console doctrine:migrations:migrate --env=test
docker compose exec server php bin/console doctrine:fixtures:load --env=test

rm tests/App/Trip/Destination/Export/export_target/file.csv

docker exec -it -e XDEBUG_MODE=coverage trip__test_server  ./vendor/bin/phpunit tests/App/Trip/Destination/  --coverage-html reports/
