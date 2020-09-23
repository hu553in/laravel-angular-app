# laravel-angular-app

## Description

This repository contains a sample web app built with Laravel and Angular.

## Tech stack

* Laravel
* Angular
* MySQL
* nginx

## API

The project has an API documentation written in OpenAPI 3.0.0.\
You can explore it prettily rendered on
[Swagger Editor](https://editor.swagger.io/?url=https://raw.githubusercontent.com/hu553in/laravel-angular-app/master/openapi.yml).\
Also you can see it's source code in [./openapi.yml](./openapi.yml).

## How to run

### Docker

1. Install `GNU Make`, `Docker`, `Docker Compose`
2. Create a relative symlink to [GUI](https://github.com/hu553in/laravel-angular-app-gui)
named `./gui` (e.g. `ln -rs ../laravel-angular-app-gui ./gui`)
3. Run `make createDotEnvForDocker` in case if you want to (re-)create `./.env` file
4. Run `make run`
4. Run `make generateSecrets` in the another terminal window in case if you want to (re-)generate several app secrets in `./.env` file
6. Run `make prepareDatabase` in the another terminal window in case if you want to (re-)migrate and (re-)seed the database

**Note:** You must definitely do steps 5-6 in case if you haven't run them before in your current environment.
