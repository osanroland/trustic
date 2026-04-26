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

## Running the tests

```bash
php bin/phpunit
```

## Running the application

```bash
symfony serve
```

The application will be available at **https://127.0.0.1:8000**.

---

## Munkaidőnapló

- **Tervezés és projekt setup** (tervezés  + terv validáicó AI-al, függőségek telpítése, alap setup +  seed): 0 – 1,5 óra
- **Lista oldal, Új értékelés oldal** (repository, controllerek, keresés funkció) : 1,5 – 3,5 óra
- **Értékelés részletek, Cégstatisztika**: 3,5 – 4 óra
- **Bónusz:** (azonos email + cég ellenőrzés, pagináció): 4 - 4,5 óra
- **Tesztek** : kb 4,5 - 5 óra

**Összesen: ~ 5 óra**
