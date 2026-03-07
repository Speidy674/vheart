#!/usr/bin/env sh

rm -f /var/www/html/bootstrap/cache/*.php

if [ -f /var/www/html/frankenphp ]; then
    echo "Using FrankenPHP..."
    exec /usr/bin/php -d variables_order=EGPCS /var/www/html/artisan octane:start --server=frankenphp --host=0.0.0.0 --admin-port=2019 --port=80 --watch
else
    echo "Using Artisan Serve..."
    exec /usr/bin/php -d variables_order=EGPCS /var/www/html/artisan serve --host=0.0.0.0 --port=80
fi
