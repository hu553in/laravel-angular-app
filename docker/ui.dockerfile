FROM node:latest

RUN groupmod -g 1001 node && usermod -u 1001 -g 1001 node

RUN groupadd -g 1000 laravel_angular_app
RUN useradd -u 1000 -ms /bin/bash -g laravel_angular_app laravel_angular_app

ARG APT_KEY_DONT_WARN_ON_DANGEROUS_USAGE=1
RUN wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add -
RUN sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" \
>> /etc/apt/sources.list.d/google.list'
RUN apt-get update && apt-get install -yq google-chrome-stable

ADD --chown=laravel_angular_app:laravel_angular_app \
https://bin.equinox.io/c/4VmDzA7iaHb/ngrok-stable-linux-amd64.zip /opt
RUN unzip -d /opt /opt/ngrok-stable-linux-amd64.zip && \
rm /opt/ngrok-stable-linux-amd64.zip
