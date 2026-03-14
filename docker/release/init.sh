#!/bin/sh

echo "[Init] Running Migrations..."
production migrate --force

echo "[Init] Clearing old caches..."
production optimize:clear

echo "[Init] Seeding Database..."
production db:seed --force

echo "[Init] Optimize..."
production optimize

chown -R www-data:www-data /app/storage /app/bootstrap/cache
chmod -R 775 /app/storage /app/bootstrap/cache

echo "[Init] Setup Storage..."
production storage:link --force

echo "[Init] Restarting Queue Signal..."
production queue:restart

echo "[Init] Done."
