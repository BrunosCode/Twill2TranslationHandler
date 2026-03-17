# Development Guide

## Requirements

- PHP 8.1+
- Composer
- Node.js (for Twill asset build)

## Setup

```bash
# Install dependencies
composer install

# Build the workbench environment (creates SQLite DB, runs migrations, seeds)
composer build
```

> **Note:** `translation-handler:install` is NOT required for development. Our capsule migrations are self-contained: they CREATE the base tables if they don't exist, or ALTER them if they were already created by the base package.

## Running the dev server

```bash
# Build + serve (recommended)
composer serve

# Or build and serve separately
composer build
php vendor/bin/testbench serve
```

The admin panel is available at `http://localhost:8000/admin`.

Default credentials: `test@test.test` / `test@test.test`

## Resetting the database

```bash
# Delete the SQLite database and rebuild
rm -f vendor/orchestra/testbench-core/laravel/database/database.sqlite
composer build
```

Our capsule migrations are self-contained (CREATE-or-ALTER pattern), so `composer build` always works from a clean state without any additional setup.

> **Note:** If you previously ran `translation-handler:install`, published migrations may exist in `vendor/orchestra/testbench-core/laravel/database/migrations/`. These can conflict with tests. Remove them with:
> ```bash
> rm -f vendor/orchestra/testbench-core/laravel/database/migrations/*translation*
> ```

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

> **Note:** Tests use an in-memory SQLite database with inline table definitions in `tests/TestCase.php`. They do not depend on published migrations.

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
├── DatabaseHandler.php                     # Custom DB handler (creates groups on import)
├── TranslationsCapsuleServiceProvider.php  # Registers Twill capsule + navigation
├── Twill2TranslationHandlerServiceProvider.php  # Package config, views, admin routes
├── Http/Controllers/
│   └── TranslationToolsController.php      # Import/Export page controller
└── Twill/Capsules/Translations/            # Twill capsule (all modules)
    ├── Models/
    │   ├── Translation.php                 # translation_keys table
    │   ├── TranslationTranslation.php      # translation_values table
    │   └── TranslationGroup.php            # translation_groups table
    ├── Http/Controllers/
    │   ├── TranslationController.php       # Keys CRUD
    │   └── TranslationGroupController.php  # Groups listing + edit
    ├── Http/Requests/
    │   ├── TranslationRequest.php
    │   └── TranslationGroupRequest.php
    ├── Repositories/
    │   ├── TranslationRepository.php
    │   └── TranslationGroupRepository.php  # Repeater data <-> translation_values
    ├── database/migrations/                # ALTER TABLE + CREATE translation_groups
    ├── resources/views/admin/
    │   ├── form.blade.php                  # Translation edit form
    │   ├── create.blade.php                # Translation create modal
    │   ├── groupForm.blade.php             # Group edit form (with repeater)
    │   └── repeaters/
    │       └── translation_item.blade.php  # Repeater item (key + translated value)
    └── routes/admin.php                    # translations + translationGroups modules

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

This package uses three tables:

| Table | Created by | Purpose |
|-------|-----------|---------|
| `translation_keys` | laravel-translation-handler | Translation keys |
| `translation_values` | laravel-translation-handler | Per-locale values (FK to keys) |
| `translation_groups` | This package | Groups by key prefix |

Our capsule migrations add two columns required by Twill:

- `translation_keys.published` (default: `true`)
- `translation_values.active` (default: `true`)

These migrations include `hasTable`/`hasColumn` guards so they are safe to run in any order.

## Adding a new module inside the Translations capsule

1. Create Model, Controller, Request, Repository under the existing `src/Twill/Capsules/Translations/` directories.

2. Add the route in `src/Twill/Capsules/Translations/routes/admin.php`:

```php
Route::module('newModuleName');
```

3. Add navigation in `TranslationsCapsuleServiceProvider::registerNavigation()` if needed.

4. Reset the database (see [Resetting the database](#resetting-the-database)).

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
