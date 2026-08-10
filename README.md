# POS E-commerce

A Laravel-based POS and e-commerce platform with storefront, admin, vendor,
reseller, wholesale, inventory, accounting, employee, courier, payment, and
marketing integrations.

## Technology

- PHP 8.2+
- Laravel 12
- MySQL / MariaDB
- Blade, Bootstrap 5, JavaScript
- Vite
- Laravel Sanctum
- Spatie Laravel Permission

## Main Features

- Customer storefront, cart, checkout, orders, invoices, reviews, and refunds
- Product, category, brand, variant, stock, wholesale, and purchase management
- Admin dashboard with persistent dark and light modes
- Vendor products, orders, wallet, withdrawals, verification, and analytics
- Reseller catalog, landing pages, wallet, deposits, orders, and withdrawals
- Supplier, expense, fund, profit/loss, stock, and sales reports
- Employee attendance, leave, salary, bonus, and payment management
- Courier integration and scheduled courier status synchronization
- Payment gateway integrations
- Facebook CAPI/Page, Google Ads, TikTok Ads, pixels, and tag management
- Mobile API authentication, products, cart, and orders
- Application updater, release, backup, and license management

## Requirements

- PHP 8.2 or newer
- MySQL or MariaDB
- Composer
- Node.js and npm
- Required PHP extensions for Laravel and the integrations in use
- ionCube Loader where protected project files require it

For local Windows development, XAMPP can provide PHP and MySQL.

## Installation

Clone the project and enter its directory:

```bash
git clone <repository-url>
cd pos-ecommerce
```

Install PHP dependencies:

```bash
composer install
```

Install frontend dependencies:

```bash
npm install
```

Create the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

On Windows PowerShell, use:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

## Environment Configuration

Configure at least these values in `.env`:

```dotenv
APP_NAME="POS E-commerce"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=
```

Do not commit `.env` or expose payment, mail, social media, courier, or license
credentials.

## Database Setup

Create the configured database, then run:

```bash
php artisan migrate
php artisan db:seed
```

If the project is supplied with an existing production-compatible database,
import that database instead and run only the missing migrations.

## Run Locally

Start MySQL first, then run:

```bash
php artisan serve
```

Open:

- Storefront: <http://127.0.0.1:8000>
- Admin area: <http://127.0.0.1:8000/admin>
- Admin dashboard: <http://127.0.0.1:8000/admin/dashboard>

This project keeps legacy static asset URLs under `/public/...`. Its Artisan
launcher is configured to use the project directory as the development
document root so those assets work correctly on port 8000.

Run only one local PHP server for this project at a time.

## Frontend Development

Start Vite in development mode:

```bash
npm run dev
```

Create a production frontend build:

```bash
npm run build
```

## Scheduler

The scheduler includes courier status synchronization. For local testing:

```bash
php artisan schedule:work
```

For production, configure the standard Laravel scheduler cron entry:

```cron
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

## Useful Commands

```bash
php artisan optimize:clear
php artisan cache:clear
php artisan view:clear
php artisan route:list
php artisan migrate:status
```

Initialize missing vendor wallets when required:

```bash
php artisan vendor:wallets:init
```

Check available custom command names and options with:

```bash
php artisan list
```

## Testing

Run the PHP test suite:

```bash
php artisan test
```

The current automated test coverage is minimal. Add feature tests for order
placement, payments, stock changes, vendor/reseller wallet transactions,
refunds, and updater operations before production-critical changes.

## Storage and Cache

Runtime files under `storage/framework`, log files, cached configuration, and
compiled Blade views are generated files and should not be committed.

If public storage uses Laravel's storage disk, create the link with:

```bash
php artisan storage:link
```

Always back up the database and uploaded files before an update or deployment.

## Project Structure

```text
app/                  Application logic, models, services, and controllers
config/               Laravel and integration configuration
database/             Migrations, factories, and seeders
project/docs/         Project-specific documentation
public/               Public assets and uploads
resources/views/      Blade templates
routes/               Web, API, console, and broadcast routes
storage/              Runtime cache, sessions, logs, and generated files
tests/                 Automated tests
```

## Security

- Keep `APP_DEBUG=false` in production.
- Never commit `.env` or API credentials.
- Restrict access to backups and updater packages.
- Use HTTPS for production payment, webhook, and authentication traffic.
- Review permissions before enabling vendor, reseller, or employee accounts.
- Back up the database and uploads before migrations or application updates.

## License

This project contains application-specific and potentially protected code.
Use and redistribution are subject to the license supplied by the project
owner.
