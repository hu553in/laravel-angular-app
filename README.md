# laravel-angular-app

## Description

This repository contains a sample web app built with Laravel and Angular.

## Tech stack

* Laravel
* Angular
* MySQL
* nginx

## API

See [openapi.yml](./openapi.yml).
You can use [Swagger Editor](https://editor.swagger.io) to render it as HTML.

## How to run

1. Install `GNU Make`, `Docker`, `Docker Compose`
2. Create relative symlink to [GUI](https://github.com/hu553in/laravel-angular-app-gui)
named `./gui` (e.g. `ln -rs ../laravel-angular-app-gui ./gui`)
3. Run `make run`
