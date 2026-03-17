# Development Guide

## Requirements

- PHP 8.1+
- Composer
- Node.js (for Twill asset build)

## Setup

```bash
# Install dependencies
composer install

# Install the translation handler package (publishes config + migrations)
php vendor/bin/testbench translation-handler:install

# Build the workbench environment (creates SQLite DB, runs migrations, seeds)
composer build
```

## Running the dev server

```bash
# Build + serve (recommended)
composer serve

# Or build and serve separately
composer build
php vendor/bin/testbench serve
```

The admin panel is available at `http://admin.localhost:8000`.

Default credentials: `test@test.test` / `test@test.test`

> **Note:** The Twill admin uses a subdomain (`admin.localhost`). Make sure your `/etc/hosts` has `127.0.0.1 admin.localhost` or access it via `curl -H "Host: admin.localhost" http://127.0.0.1:8000`.

## Common commands

### Tests

```bash
# Run all tests
composer test

# Run with coverage
composer test-coverage

# Run a specific test file
vendor/bin/pest tests/TranslationModelTest.php

# Run a specific test
vendor/bin/pest --filter="can create a translation key"
```

### Code style (Pint)

```bash
# Fix code style
composer format

# Or directly
vendor/bin/pint
```

### Static analysis (PHPStan)

```bash
# Run analysis
composer analyse

# Or with verbose output
vendor/bin/phpstan analyse --verbose --memory-limit 300M
```

### Lint (Pint + PHPStan together)

```bash
composer lint
```

### Artisan / Tinker via Testbench

```bash
# Run any artisan command
php vendor/bin/testbench <command>

# Examples
php vendor/bin/testbench migrate
php vendor/bin/testbench migrate:rollback
php vendor/bin/testbench migrate:fresh
php vendor/bin/testbench tinker
php vendor/bin/testbench route:list
php vendor/bin/testbench db:seed --class="Workbench\\Database\\Seeders\\DatabaseSeeder"
```

### Translation Handler commands

```bash
# Import translations (PHP files -> DB)
php vendor/bin/testbench translation-handler:import

# Export translations (DB -> PHP files)
php vendor/bin/testbench translation-handler:export

# Get a single translation
php vendor/bin/testbench translation-handler:get db test.key en

# Set a single translation
php vendor/bin/testbench translation-handler:set db test.key en "value"
```

## Project structure

```
src/
├── DatabaseHandler.php                     # Custom DB handler (extends base package)
├── TranslationsCapsuleServiceProvider.php  # Registers Twill capsules + navigation
├── Twill2TranslationHandlerServiceProvider.php  # Package config, views, admin routes
├── Http/Controllers/
│   └── TranslationToolsController.php      # Import/Export page controller
└── Twill/Capsules/Translations/            # Twill capsule
    ├── Models/
    │   ├── Translation.php                 # Uses translation_keys table
    │   └── TranslationTranslation.php      # Uses translation_values table
    ├── Http/
    │   ├── Controllers/TranslationController.php
    │   └── Requests/TranslationRequest.php
    ├── Repositories/TranslationRepository.php
    ├── database/migrations/                # ALTER TABLE migrations (add published, active)
    ├── resources/views/admin/
    └── routes/admin.php

config/
└── translation-handler.php     # Package config (locales, file names, handlers, paths)

resources/views/tools/
└── index.blade.php             # Import/Export admin page

workbench/                      # Dev/test environment (orchestra/testbench)
├── config/
│   ├── twill.php
│   └── twill-navigation.php
├── database/seeders/           # Admin user seeder
└── routes/
```

## Database

This package relies on two tables from `brunoscode/laravel-translation-handler`:

- `translation_keys` — stores translation keys
- `translation_values` — stores per-locale values (FK to translation_keys)

Our capsule migrations add two columns required by Twill:

- `translation_keys.published` (default: `true`)
- `translation_values.active` (default: `true`)

These migrations include `hasTable`/`hasColumn` guards so they are safe to run in any order.

## Adding a new capsule

1. Create the capsule directory under `src/Twill/Capsules/<Name>/` following the Twill 2 structure (Models, Controllers, Requests, Repositories, views, routes, migrations).

2. Register it in `TranslationsCapsuleServiceProvider::boot()`:

```php
$this->registerCapsuleWithoutNavigation('NewCapsule');
```

3. Add navigation in `registerNavigation()` if needed.

4. Run `composer build` to apply migrations.

## Updating dependencies

```bash
# Update all
composer update

# Update a specific package
composer update brunoscode/laravel-translation-handler

# Update to a new tagged version (edit composer.json first)
# "brunoscode/laravel-translation-handler": "^0.1.7"
composer update brunoscode/laravel-translation-handler
```

## Tests and published migrations

When running `translation-handler:install`, migrations get published to `vendor/orchestra/testbench-core/laravel/database/migrations/`. These can conflict with the inline table definitions in `tests/TestCase.php`. If tests fail with "table already exists", remove the published migration files:

```bash
rm -f vendor/orchestra/testbench-core/laravel/database/migrations/*translation*
```
