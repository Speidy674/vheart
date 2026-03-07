#!/usr/bin/env bash
set -e

./vendor/bin/sail down -v

echo "Deleting stuff that may be broken..."
./clean --force

echo "Installing Composer dependencies..."
docker run --rm \
    --interactive \
    --tty \
    --volume "$PWD":/app \
    --user "$(id -u):$(id -g)" \
    composer install --ignore-platform-reqs

if [[ "$1" == "--build" ]]; then
    echo "Building Sail (can take a while)..."
    ./vendor/bin/sail build --no-cache
fi

echo "Starting Sail..."
./vendor/bin/sail up -d

until ./vendor/bin/sail ps | grep "laravel.test" | grep -q "(healthy)"; do
    echo "waiting for sail to be healthy..."
    sleep 1
done
echo "Sail Healthy!"

echo "(re)Installing dependencies using sail..."
./vendor/bin/sail composer install --quiet && echo ">> Composer Ready." &
COMPOSER_PID=$!

./vendor/bin/sail npm install --quiet > /dev/null && echo ">> NPM Ready." &
NPM_PID=$!

./vendor/bin/sail artisan octane:install --server=frankenphp --no-interaction > /dev/null 2>&1 && echo ">> FrankenPHP Ready." &
FRANKEN_PID=$!

wait $COMPOSER_PID
wait $NPM_PID

# make it think we got dev-mode running so laravel doesnt scream because of missing manifest files
touch ./public/hot

echo "Resetting stuff..."
./vendor/bin/sail artisan migrate:fresh > /dev/null && echo ">> Database Rebuild" &
DB_MIGRATE_PID=$!

./vendor/bin/sail composer helper > /dev/null && echo ">> Helper files Generated" &
HELPER_PID=$!

wait $DB_MIGRATE_PID

LOG_CHANNEL=null ./vendor/bin/sail artisan db:seed && echo ">> Database Seeded" &
DB_SEEDER_PID=$!

wait $FRANKEN_PID
wait $HELPER_PID
wait $DB_SEEDER_PID
./vendor/bin/sail down

rm -f ./public/hot

echo "everything should be fixed now"
