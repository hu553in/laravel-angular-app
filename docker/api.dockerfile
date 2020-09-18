FROM php:fpm-stretch

RUN apt-get update && apt-get install -y --no-install-recommends libzip-dev unzip zip git

RUN docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 laravel_angular_app
RUN useradd -u 1000 -ms /bin/bash -g laravel_angular_app laravel_angular_app
