# App

This folder contains all back end related things.
## Requirements
:heavy_check_mark: Make sure `Postgres v12.x.x` installed on your machine.

:heavy_check_mark: Make sure `PHP v7.4.x` installed on your machine.

:heavy_check_mark: Make sure `Composer v2.x.x` installed on your machine.

:heavy_check_mark: Make sure `Laravel v6.x.x` installed on your machine.

:heavy_check_mark: Make sure `BCMath, GMP` extenstion enabled on your machine.

## Environment Variables

we used environment variables to change the view of staging, and development.


**Full development .env**

```bash
DB_CONNECTION=pgsql # Make sure Postgresql is running 
DB_HOST=127.0.0.1 # Postgresql host
DB_PORT=5432 # Postgresql port
DB_DATABASE=apricot # Database name
DB_USERNAME=postgres # Database user
DB_PASSWORD=postgres # Database password
# Please checkout .env.example or .env.staging for more infomation
```

## Usage

Make sure postgresql is running, and given requirements installed on your machine.

```bash
# Package installation
$> composer install

# Databse migration
$> php artisan migrate

# Optional: Seed the database with example data
$> php artisan db:seed

# Optional: See the route list
$> php artisan route:list

# Optional: Tinker allows you to interact with your entire Laravel application on the command line
$> php artisan tinker

# Run the test cases
$> ./vendor/bin/phpunit

# Start API
$> php artisan serve
```
## Linting
Make sure you menually do before the commit

```bash
# Below Commnad will show Errors in each file which has linting errors
composer run-script lint

# Below Command Will Automatically fix Not Critical errors in project
composer run-script lint-fix

# Below Command will show diff after and before it apply auto-fix on linting errors
composer run-script lint-diff
```
## Useful Links

Useful links for back end development.
- [Google form webhook setup](./GoogleFormsWebhook.md)
- `Laravel` - [https://laravel.com/docs/6.x](https://laravel.com/docs/6.x)
- `Postgres` - [https://www.postgresql.org/docs/12/index.html](https://www.postgresql.org/docs/12/index.html)