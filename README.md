# laravel-angular-app

## Description

This repository contains a sample web app built with Laravel and Angular.

## Tech stack

* Laravel
* Angular
* MySQL
* nginx

## API

The project has an API documentation written in OpenAPI 3.0.3.\
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
5. Run `make generateSecrets reconfigure` in the another terminal window in case if
you want to (re-)generate several app secrets in `./.env` file
7. Run `make prepareDatabase` in the another terminal window in case if you want to
(re-)migrate and (re-)seed the database
8. Run `make exposeViaNgrok` in the another terminal window in case if you want to
share your app with an outside world using `ngrok`
9. Run `make runTestsBackend` in the another terminal window in case if you want to
run backend unit and feature tests
10. Run `make runTestsFrontendCommon` in the another terminal window in case if you want
to run frontend common tests
11. Run `make runTestsFrontendEndToEnd` in the another terminal window in case if you
want to run frontend end-to-end tests

**Note:** You definitely must do steps 3, 5, 6 in case if you haven't run them before in your current environment.
