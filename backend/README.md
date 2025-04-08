# Backend Setup (Symfony API)

This folder contains the backend of the application, built with Symfony. It serves as the API for the frontend.

## Requirements

- PHP 8.0+
- Composer
- Symfony CLI (optional, but recommended)
- Database (PostgreSQL)
- Docker (for containerized environment)

## Setup Instructions

Before working with the database, you need to build and start the Docker containers. Run the following command to do that:

```bash
docker-compose up -d
```

### 1\. Install Dependencies

Run the following command to install backend dependencies:

`composer install`

### 2\. Configure Environment Variables

If environment variable is missing, edit the `.env` file to match your local environment (e.g., database credentials):

`DATABASE_URL="postgresql://app:docker@127.0.0.1:5432/app?serverVersion=16&charset=utf8"`

### 3\. Create the Database

NB! If you're using Windows add `php` before `bin/console` command in steps 3, 4 and 5.

Create the database using the following command:

`bin/console doctrine:database:create`

### 4\. Run Migrations

To apply the migrations, run the following commands:

`bin/console make:migration`

`bin/console doctrine:migrations:migrate`

This will create the necessary database schema.

### 5\. Load Fixtures

Once the migrations are complete, you need to populate the `sector` table with initial data. You can do this by running the following command:

`bin/console doctrine:fixtures:load`

Type `yes` to continue.

This will load predefined data (e.g., sector names) into the `sector` table.

### 6\. Start the Symfony Server

If you want to run the Symfony server locally, use the Symfony CLI:

`symfony serve`
