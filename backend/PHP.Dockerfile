FROM php:fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev mariadb-client \
    && docker-php-ext-install pdo pdo_mysql \
    && curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

ENV COMPOSER_ALLOW_SUPERUSER=1

CMD ["sh", "-lc", "\
echo 'Waiting for MySQL...' && \
until mysqladmin ping -hmysql -uroot -psecret123 --silent; do \
    sleep 2; \
done && \
echo 'MySQL ready' && \
[ -f vendor/autoload.php ] || composer install --no-interaction --no-progress && \
mysql -hmysql -uroot -psecret123 -e 'DROP DATABASE IF EXISTS developmentdb; CREATE DATABASE developmentdb;' && \
composer phinx migrate && \
composer phinx seed:run && \
echo 'Starting PHP-FPM...' && \
php-fpm \
"]