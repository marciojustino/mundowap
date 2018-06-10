FROM php:7.1.4-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick mysql \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql mysqli zip

WORKDIR /api
COPY ./server/composer.json /api/composer.json
COPY ./server/composer.lock /api/composer.lock

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install