#!/bin/sh
set -eu

if [ "${1:-}" = "apache2-foreground" ]; then
    : "${APP_KEY:?APP_KEY belum diisi. Jalankan php artisan key:generate pada host.}"

    mkdir -p /var/www/data storage/app/public storage/framework/cache/data \
        storage/framework/sessions storage/framework/views storage/logs

    if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
        : "${DB_DATABASE:=/var/www/data/database.sqlite}"
        export DB_DATABASE
        if [ ! -f "$DB_DATABASE" ]; then
            touch "$DB_DATABASE"
        fi
    fi

    chown -R www-data:www-data /var/www/data storage bootstrap/cache

    if [ ! -e public/storage ]; then
        php artisan storage:link --no-interaction
    fi

    # Jalankan sebelum HTTP dan worker menerima pekerjaan.
    php artisan migrate --force --no-interaction
fi

exec docker-php-entrypoint "$@"
