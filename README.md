# Is It Down Checker

## How it works

Add as many urls as you would like. The app will check the url once every 5 minutes. If the url responds with an invalid it will report the url as "down." Once the site has been down for 10 minutes the app will create a task in Teamwork (you can add your own task manager and swap out the binding in `\App\Providers\AppServiceProvider`. Once the url comes back up the app will auto-complete the task.

The url is considered "down" if it meets the following conditions:

- Response code is not 2xx
- Certificiate will expire within 30 days

## Prerequisites

- PHP (^7.1.3)
- Laravel - [Learn Laravel](https://laravelfromscratch.com)
- Database (MariaDB, PostgreSQL, MySQL)
- Server (NGINX/Apache)

## Getting started

1. Clone this repo
2. Add it to your local environment
3. Copy `.env.example` to `.env` and configure the values (including the db)
4. Run `composer install`
5. Set up cron job - [Laravel documentation](https://laravel.com/docs/5.7/scheduling#introduction)