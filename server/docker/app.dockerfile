FROM php:7.1.4-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick mysql \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql mysqli

ADD ./docker/php.ini /usr/local/etc/php/php.ini