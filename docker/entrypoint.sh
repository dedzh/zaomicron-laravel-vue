#!/usr/bin/env bash
set -e

until php -r "new PDO('mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" >/dev/null 2>&1; do
  echo "Waiting for MySQL..."
  sleep 3
done

php artisan migrate --force
php artisan db:seed --force || true

exec apache2-foreground
