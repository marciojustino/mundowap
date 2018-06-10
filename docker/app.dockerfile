FROM php:7.1.4-fpm

RUN apt-get update && apt-get install -y libmcrypt-dev \
    mysql-client libmagickwand-dev --no-install-recommends \
    && pecl install imagick mysql \
    && docker-php-ext-enable imagick \
    && docker-php-ext-install mcrypt pdo_mysql mysqli

ADD ./docker/php.ini /usr/local/etc/php/php.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

ADD ./server /app
WORKDIR /app
RUN composer install --no-plugins --no-scripts
RUN composer dump-autoload --optimize && composer run-scripts post-install-cmd