# Codeigniter & Svelte Note Application

## Purpose

Part of a bachelor's thesis exploring the usage of single page applications and JWTs.

## Installation

You need to have PHP, Composer and npm installed on your System.

- `composer update` to download PHP packages
- `npm install` to download JS dependencies

## Setup

Copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings. As well as setting a JWT secret key `JWT_SECRET_KEY` and token validity periods

- run `php spark serve`
- run `npm run dev`

Build database with
- `php spark migrate`

Add optional sample Data to the Database with
- `php spark db:seed UserSeeder`
- `php spark db:seed NoteSeeder`
- `php spark db:seed CategorySeeder`

For deployment set environment to `production` in `.env`.
Compile JS with

- `npm run build`

## Server Requirements

PHP version 7.3 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)
