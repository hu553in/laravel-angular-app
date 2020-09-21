FROM php:fpm-stretch

RUN apt-get update && apt-get install -y --no-install-recommends libzip-dev unzip zip git

RUN docker-php-ext-install zip pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 laravel_angular_app
RUN useradd -u 1000 -ms /bin/bash -g laravel_angular_app laravel_angular_app

ADD --chown=laravel_angular_app:laravel_angular_app \
https://raw.githubusercontent.com/vishnubob/wait-for-it/master/wait-for-it.sh /opt/
RUN chmod +x /opt/wait-for-it.sh
