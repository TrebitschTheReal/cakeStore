# CakeShop

This my degree project, where you can: 
- see the guest design if you are not logged in yet (not much here at the moment)
- search, and manage stock, build recipes, and create / modify ingredients if you have manager role
- manage users, create new accounts if you have admin privilige.

## Project setup for devs

Project requirements:

- php7.2 at least
- Composer installed
- Laravel installed
- 10.4.10 MariaDB or 8.0.18 MySql

Clone the project:
```
git clone git@github.com:TrebitschTheReal/cakeShop.git
cd cakeShop
```
Create your .env file, and set your database connection:

```
cp .env.example .env
```

Install dependencies:
```
composer install
npm i
```
Generate app key:
```
php artisan key:generate
````
Create database with utf8mb4 encoding:
```
CREATE DATABASE laravel CHARACTER SET UTF8mb4 COLLATE utf8mb4_unicode_ci;
```
Run fresh migration with seed

```
php artisan migrate:fresh --seed
```

Watcher with hot reload:
```
npm run watch
```

Start server in localhost - localhost:8000:
```
php artisan serve
```

(or you can set a virtual host with nginx / apache if you would like.)

You're all set!
