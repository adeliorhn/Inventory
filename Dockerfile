FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --optimize-autoloader

COPY . .
RUN composer dump-autoload --optimize

FROM php:8.4-cli-alpine

RUN apk add --no-cache \
        bash \
        icu-dev \
        libpq-dev \
        oniguruma-dev \
    && docker-php-ext-install \
        bcmath \
        intl \
        mbstring \
        opcache \
        pdo_pgsql \
        pgsql

WORKDIR /var/www/html

COPY --from=vendor /app /var/www/html
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-inventory.ini
COPY docker/php/entrypoint.sh /usr/local/bin/inventory-entrypoint

RUN chmod +x /usr/local/bin/inventory-entrypoint \
    && mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8000

ENTRYPOINT ["inventory-entrypoint"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
