# Quote Manager Backend

This project is a Laravel 10 application with JWT (JSON Web Token) authentication for user registration and login, and Swagger documentation for API endpoints.

## Requirements

-   PHP >= 8.0
-   Composer
-   Laravel 10
-   MySQL / Postgres (or any other supported database)

## Installation

Follow these steps to set up the project on your local machine.


### Step 1: Clone the Repository

Clone the repository to your local machine.


```bash
git clone https://github.com/kimmleang/quote-manager-backend
cd quote-manager-backend
```

Copy the example environment file and configure it.
```bash 
cp .env.example .env
```

Edit the .env file to match your database configuration and other settings. Make sure to set the following variables:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```
Generate the application key.

```bash
php artisan key:generate
```

Generate the JWT secret key.

```bash
php artisan jwt:secret
```

Run the database migrations to create the necessary tables.

```bash
php artisan migrate
```

Seeding data

```bash
php artisan db:seed
```

Start the local development server.

```bash
php artisan serve
```
