# Run backend

## Installation and configuration

Install **PHP**, **MySQL**, **Apache** and **Composer** before running this project

**Create .env file based on .env.example and config your database connection**

`cd path/to/backend/folder` to go to back-end folder  
`composer install` to install dependencies  
`composer update` to update dependencies (optional)  
`php artisan key:generate` to generate key

## Migration and seeding 

Run `php artisan migrate` to migrate database  
(if you want to reset database, run `php artisan migrate:refresh`)

Run `php artisan db:seed` to seed database  

**OR** you can run this command: `php artisan migrate:fresh --seed` to reset and seed database (migrate and seeding at the same time)

## Run project

`php artisan serve` to run the project  
`php artisan serve --port=[PORT]` to run the project on port [PORT]
