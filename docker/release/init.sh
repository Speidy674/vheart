#!/bin/sh

echo "[Init] Running Migrations..."
php /app/artisan migrate --force

echo "[Init] Clearing old caches..."
php /app/artisan optimize:clear

echo "[Init] Seeding Database..."
php /app/artisan db:seed --force

echo "[Init] Optimize..."
php /app/artisan optimize

echo "[Init] Setup Storage..."
php /app/artisan storage:link --force

echo "[Init] Export translation files..."
php /app/artisan translations:export

echo "[Init] Restarting Queue Signal..."
php /app/artisan queue:restart

echo "[Init] Done."
