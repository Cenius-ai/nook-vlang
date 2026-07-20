# Installation Guide

## 1. Prerequisites

Ensure your system meets the following requirements:

- **Python** ≥ 3.11 (required by some tooling)
- **Node.js** ≥ 20.x
- **PHP** ≥ 8.2 (Composer dependency requires `^8.1`, but we recommend 8.2+)
- **Composer** (get it from [getcomposer.org](https://getcomposer.org))

## 2. Get the Code

Clone the project repository:

```bash
git clone <repository-url>
cd nook
```

## 3. Install Dependencies

Install PHP packages with Composer:

```bash
composer install
```

Install frontend dependencies (if any) with npm:

```bash
npm install
```

## 4. Environment Setup

1. Create a `.env` file by copying the example:

   ```bash
   cp .env.example .env
   ```

2. Generate an application key:

   ```bash
   php artisan key:generate
   ```

3. Edit `.env` to set your preferred database connection (`DB_CONNECTION`, etc.) and other services as needed. The application uses the variables listed in [README.md](README.md#environment-variables).

## 5. Run the Application (Development)

Start the built‑in PHP development server:

```bash
php artisan serve
```

The application will be accessible at `http://localhost:8000` by default.

## 6. Running Tests

Execute the test suite:

```bash
php artisan test
```

## 7. Production Build

If you need a frontend build (currently a no‑op, as per `package.json`), you can run:

```bash
npm run build
```

## 8. Troubleshooting

- **Permission errors**: Ensure `storage/` and `bootstrap/cache/` are writable by your web server. On Unix systems, run:
  ```bash
  chmod -R 775 storage bootstrap/cache
  ```

- **Missing `.env`**: If you forget to copy `.env.example`, the application will prompt you to run `composer run-script post-root-package-install` (which normally happens automatically).

- **Port already in use**: Use `php artisan serve --port=8080` to pick a different port.

- **Database errors**: Verify your `DB_CONNECTION` and related settings in `.env`. Run migrations if needed (default Laravel command: `php artisan migrate`).