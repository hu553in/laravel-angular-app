FROM node:slim

RUN groupmod -g 1001 node && usermod -u 1001 -g 1001 node

RUN groupadd -g 1000 laravel_angular_app
RUN useradd -u 1000 -ms /bin/bash -g laravel_angular_app laravel_angular_app
