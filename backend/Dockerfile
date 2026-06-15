FROM php:fpm

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
     git unzip libzip-dev default-mysql-client nginx supervisor \
  && docker-php-ext-install pdo pdo_mysql \
  && curl -sS https://getcomposer.org/installer -o composer-setup.php \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
  && rm composer-setup.php \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . .

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN composer install --no-interaction --no-progress

COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80

CMD ["sh", "-lc", "\
  echo 'Waiting for MySQL...' && \
  until mysqladmin ping -h\"$DB_HOST\" -P\"$DB_PORT\" -u\"$DB_USER\" -p\"$DB_PASSWORD\" --silent; do \
    sleep 2; \
  done && \
  echo 'MySQL ready' && \
  composer phinx migrate && \
  composer phinx seed:run && \
  echo 'Starting services...' && \
  exec supervisord -c /etc/supervisor/conf.d/supervisord.conf \
"]