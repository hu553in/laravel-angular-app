# laravel-angular-app

## Description

This repository contains a sample web app built with Laravel and Angular.

## Tech stack

* Laravel
* Angular
* MySQL
* nginx

## API

Project has an API documentation written using OpenAPI 3.0.0.\
You can explore it prettily rendered on
[Swagger Editor](https://editor.swagger.io/?url=https://raw.githubusercontent.com/hu553in/laravel-angular-app/master/openapi.yml).\
Also you can see it's source code in [./openapi.yml](./openapi.yml).

## How to run

### Development

1. Install `GNU Make`, `Docker`, `Docker Compose`
2. Create relative symlink to [GUI](https://github.com/hu553in/laravel-angular-app-gui)
named `./gui` (e.g. `ln -rs ../laravel-angular-app-gui ./gui`)
3. Run `make run`
