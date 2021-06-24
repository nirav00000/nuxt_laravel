FROM composer:1.10.19 AS build

COPY . /src

WORKDIR /src

RUN composer install --no-dev

#======================================
# "Fast Process Manager", which is just a fancy way of saying that the PHP running on a server
FROM php:7.4-fpm-alpine

WORKDIR /app

RUN set -ex \
  && apk --no-cache add postgresql-dev gmp-dev nginx supervisor wget \
  && docker-php-ext-install bcmath gmp pdo_pgsql \
  && mkdir -p /run/nginx \
  && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy nginx configurations
COPY --from=build /src/docker/nginx.conf /etc/nginx/nginx.conf

# Copy everythings to current workdirectory
COPY --from=build /src  .

# Php-fpm need write logs permission - php fpm default user is www-data
RUN chmod -R 777 ./* \ 
    && php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"

EXPOSE 5000/tcp

# Copy configure file of supervisiord to supervisiord | -c flag for copy.
CMD /usr/bin/supervisord -c ./docker/supervisiord.conf