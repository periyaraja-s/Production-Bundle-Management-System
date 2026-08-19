# Production Bundle Management System

Apparel ERP module for managing sewing-floor production bundles. The application provides a Bootstrap 5 web UI and a JSON REST API for creating, listing, updating, and soft-deleting bundles, with live quantity calculations and a production dashboard.

Repository: [https://github.com/periyaraja-s/Production-Bundle-Management-System](https://github.com/periyaraja-s/Production-Bundle-Management-System)

## Features

- Production bundle CRUD (create, view, edit, delete)
- Shared server-side validation for web and API
- AJAX create/update on the web forms
- Real-time Balance, Efficiency %, and Rejection % on the form
- Server-side listing search, filters, sorting, and pagination
- Production dashboard with seven aggregate metrics
- REST API for bundles and dashboard metrics
- Soft deletes (records are not permanently removed)
- Seeded buyers, styles, and sewing lines

Authentication and Laravel Sanctum are **not** implemented. API endpoints are unauthenticated.

## Tech stack

- PHP 8.2+
- Laravel 11
- MySQL 8
- Bootstrap 5.3 (CDN)
- PHPUnit 10

## Requirements

- PHP 8.2 or newer with Composer
- MySQL 8
- PHP extensions required by Laravel 11 (`pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)

Node/Vite is present in the skeleton but is **not** required to run the UI (Bootstrap is loaded from CDN).

## Installation (Laravel 11 + MySQL 8)

```bash
git clone https://github.com/periyaraja-s/Production-Bundle-Management-System.git
cd Production-Bundle-Management-System

composer install
copy .env.example .env   # Windows
# cp .env.example .env   # macOS/Linux

php artisan key:generate
```

Configure MySQL in `.env`, then:

```bash
php artisan migrate
php artisan db:seed
php artisan serve
```

Open [http://127.0.0.1:8000](http://127.0.0.1:8000).

### `.env` database example

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apparel_erp
DB_USERNAME=root
DB_PASSWORD=your_password
```

Create the empty database in MySQL before migrating:

```sql
CREATE DATABASE apparel_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Migrations and seeding

| Command | Purpose |
|---|---|
| `php artisan migrate` | Create tables and indexes |
| `php artisan db:seed` | Seed buyers, styles, and sewing lines |
| `php artisan migrate:fresh --seed` | Rebuild the schema and reseed |

Seeding does **not** insert production bundles. Create those from the web UI or API.

## Web routes

| Method | Path | Description |
|---|---|---|
| GET | `/` | Production bundle dashboard |
| GET | `/production-bundles` | Paginated listing with search, filters, and sort |
| GET | `/production-bundles/create` | Create form |
| POST | `/production-bundles` | Store bundle (AJAX JSON or redirect) |
| GET | `/production-bundles/{id}` | Bundle detail |
| GET | `/production-bundles/{id}/edit` | Edit form |
| PUT/PATCH | `/production-bundles/{id}` | Update bundle (AJAX JSON or redirect) |
| DELETE | `/production-bundles/{id}` | Soft-delete bundle |

## REST API

No authentication. Send `Accept: application/json` for JSON error responses.

| Method | Path | Description |
|---|---|---|
| GET | `/api/bundles` | Paginated bundles (eager-loaded buyer, style, sewing line, plus calculated fields) |
| POST | `/api/bundles` | Create a bundle (`201`) |
| PUT | `/api/bundles/{id}` | Update a bundle |
| DELETE | `/api/bundles/{id}` | Soft-delete a bundle |
| GET | `/api/dashboard` | Seven dashboard metrics |

**List query parameters:** `page`, `per_page` (`20` default; `20`, `50`, or `100`). Invalid `per_page` values fall back to `20`.

**Typical JSON shape**

```json
{
  "success": true,
  "message": "Production bundle created successfully.",
  "data": {}
}
```

List responses include `data` and `meta` (`current_page`, `per_page`, `total`, `last_page`, `from`, `to`). Validation failures return HTTP `422` with `success`, `message`, and `errors`. Missing records return HTTP `404`.

The API list does **not** implement the web listing search/filters.

## Validation and business rules

Applied on web and API create/update:

- `bundle_no` required, unique (including soft-deleted rows); the current record is ignored on update
- `buyer_id`, `style_id`, `line_id` required and must exist
- `color` required, max 100 characters
- `size` required, max 50 characters
- `quantity` integer, minimum 1
- `completed_qty` and `rejected_qty` integers, minimum 0, each cannot exceed `quantity`
- completed + rejected cannot exceed `quantity`
- `operator_name` optional, max 150 characters
- `production_date` required, cannot be in the future
- `remarks` optional

## Real-time calculations

Displayed on the create/edit form (JavaScript) and on listing/API (PHP accessors):

| Metric | Formula |
|---|---|
| Balance | `quantity - completed_qty - rejected_qty` |
| Efficiency % | `(completed_qty / quantity) * 100` (0 if quantity is 0) |
| Rejection % | `(rejected_qty / quantity) * 100` (0 if quantity is 0) |

## Search, filters, sorting, and pagination (web listing)

- **Search:** bundle number, operator name, color, buyer name, style number
- **Filters:** buyer, style, sewing line, date from, date to
- **Sort:** bundle number, buyer, style, quantity, efficiency, production date
- **Pagination:** 20 / 50 / 100 per page (default 20), server-side
- Default order: `production_date` descending, then `id` descending

## Dashboard

Web (`/`) and API (`/api/dashboard`) use one SQL aggregate query (not a full-table PHP load):

- Total Bundles
- Total Quantity
- Total Completed
- Total Rejected
- Average Efficiency
- Today's Production (sum of `quantity` for today's `production_date`)
- Today's Rejection (sum of `rejected_qty` for today)

## Testing

```bash
php artisan test
```

Feature tests cover web validation and the REST API. Tests use `RefreshDatabase` against the database configured for the app (phpunit does not force SQLite). Use a dedicated test database if you do not want local data reset.

## Performance considerations

Designed to remain efficient with large bundle volumes (assessment target: 50,000+ rows):

- Server-side pagination (`LIMIT`/`OFFSET`); listings do not load all rows into PHP
- Eager loading of buyer, style, and sewing line on list endpoints (no N+1 on table rows)
- Dashboard metrics computed with `COUNT`/`SUM`/`AVG` in SQL
- Date filters use `production_date >=` / `<=` so the date index can be used
- Indexes include FKs, `production_date`, unique `bundle_no`, composite `(deleted_at, production_date)`, `buyers.buyer_name`, and `styles.style_no`

## GitHub usage notes

- Clone with Git, then follow the installation steps above.
- Do not commit `.env` or application secrets. Use `.env.example` as the template.
- Push feature work to a branch and open a pull request against the GitHub remote.
- After pulling, run `composer install` and `php artisan migrate` if dependencies or schema changed.
