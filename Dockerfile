FROM php:8.2-fpm

RUN docker-php-ext-install sockets

RUN apt-get update && apt-get install -y git libcurl4-openssl-dev pkg-config libssl-dev zip

RUN pecl install mongodb \
    &&  echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongo.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir /app

COPY . /app

WORKDIR /app

