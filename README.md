# Trustic

## Requirements

- PHP 8.4
- Composer
- Symfony CLI
- SQLite

## Setup

1. Install dependencies:
   ```bash
   composer install
   ```

2. Create a `.env.local` file in the project root and add the following:
   ```env
   DATABASE_URL="sqlite:///%kernel.project_dir%/var/trustic.db"
   ```

3. Run the database migrations:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

4. To populate the database with 15 sample reviews:

    ```bash
    php bin/console doctrine:fixtures:load
    ```

## Running the application

```bash
symfony serve
```

The application will be available at **https://127.0.0.1:8000**.
