# Client Management
- PHP 7.3.14
- Laravel 6.20.44
- Axios

## How to use

### Preparation
- Make sure you have PHP and Composer installed globally on your computer.
- Clone the repo and enter the project folder

```
git clone https://github.com/alexeymezenin/laravel-realworld-example-app.git
cd laravel-realworld-example-app
```

### Install the app

```
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
```

### Install the Database and create Dummy Data 
- Make sure to create database `client_management` on your PHPMyAdmin
- Do not run `php artisan db:seed` to avoid error, follow every step below

```
php artisan migrate
php artisan create:admin-role
php artisan db:seed --UserSeeder
php artisan db:seed --OrganizationSeeder
php artisan db:seed --PersonSeeder
```

Run the web server

```
php artisan serve
```

That's it. Now you can use the app with credential below
```
email: admin@clientmanagement.com
password: 123456
```
