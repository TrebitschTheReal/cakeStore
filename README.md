# CakeShop

This my degree project, where you can: 
- see the guest design if you are not logged in yet
- search, and manage stock, build recipes, and create / modify ingredients if you have manager role
- manage users if you have admin privilige.

## Project setup for devs

Project requirements:

- php7.2 at least
- Composer installed
- Laravel installed
- MariaDB installed

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
