FROM php:fpm

# Install system dependencies and Composer

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev nginx gettext-base \
    && docker-php-ext-install pdo pdo_mysql \
    && curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Allow running Composer as root within the container

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY . /app
COPY nginx.conf /etc/nginx/nginx.conf

RUN chown -R www-data:www-data /app \
    && chmod -R 775 /app

EXPOSE 10000

# On container start, install dependencies if vendor is missing, then start php-fpm and nginx

CMD ["sh", "-lc", "[ -f vendor/autoload.php ] || composer install --no-interaction --no-progress; sed \"s/__PORT__/${PORT:-10000}/g\" /etc/nginx/nginx.conf > /tmp/nginx.conf && mv /tmp/nginx.conf /etc/nginx/nginx.conf; php-fpm -D; exec nginx -g 'daemon off;'"]