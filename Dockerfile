# syntax=docker/dockerfile:1

FROM php:8.3-apache-bookworm AS php-base
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libonig-dev libsqlite3-dev libzip-dev unzip \
    && docker-php-ext-install -j"$(nproc)" mbstring pdo_sqlite pdo_mysql zip pcntl opcache \
    && a2enmod rewrite \
    && sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel \
    && rm -rf /var/lib/apt/lists/*
RUN apt-get update \
    && apt-get install -y --no-install-recommends libicu-dev \
    && docker-php-ext-install -j"$(nproc)" intl \
    && rm -rf /var/lib/apt/lists/*
WORKDIR /var/www/html

FROM php-base AS dependencies
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --no-interaction --no-progress
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-interaction

FROM node:22-bookworm-slim AS assets
WORKDIR /app
COPY package.json package-lock.json .npmrc ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./
COPY --from=dependencies /var/www/html/vendor/laravel/framework/src/Illuminate/Pagination/resources/views ./vendor/laravel/framework/src/Illuminate/Pagination/resources/views
RUN npm run build

FROM php-base AS app
COPY --from=dependencies --chown=www-data:www-data /var/www/html /var/www/html
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build
COPY --chmod=755 docker-entrypoint.sh /usr/local/bin/app-entrypoint
RUN mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs /var/www/data \
    && chown -R www-data:www-data storage bootstrap/cache /var/www/data
EXPOSE 80
ENTRYPOINT ["app-entrypoint"]
CMD ["apache2-foreground"]
