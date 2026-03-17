# Twill Translation Handler

[![Latest Version on Packagist](https://img.shields.io/packagist/v/brunoscode/twill-translation-handler.svg?style=flat-square)](https://packagist.org/packages/brunoscode/twill-translation-handler)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/brunoscode/twill-translation-handler/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/brunoscode/twill-translation-handler/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/brunoscode/twill-translation-handler.svg?style=flat-square)](https://packagist.org/packages/brunoscode/twill-translation-handler)

A [Twill CMS](https://twillcms.com) capsule for managing Laravel translations directly from the admin panel. Built on top of [LaravelTranslationHandler](https://github.com/BrunosCode/LaravelTranslationHandler).

> **Twill compatibility:** Currently supports **Twill 2**. Support for Twill 3 is planned for a future release.

## Features

- **Translation key management** — Browse and edit all translation keys and their per-locale values from a clean Twill module interface.
- **Group-based organization** — Translations are automatically grouped by prefix (e.g. `messages`, `messages.validation`). Groups are created automatically on import and provide a focused editing view via Twill repeaters.
- **Multi-locale support** — Edit translations for all configured locales side by side. Individual locale values can be marked active or inactive.
- **Import / Export** — Sync translations between the database, PHP language files, and CSV files:
  - Export all translations to PHP or CSV
  - Import from PHP files or upload a CSV
  - Export/import a single group as CSV directly from its edit page
- **Automatic group syncing** — When translations are imported, the `DatabaseHandler` automatically creates all intermediate group levels (e.g. importing `messages.validation.required` creates both `messages` and `messages.validation` groups).

## Requirements

- PHP 8.1+
- Laravel 10+
- Twill 2.x
- [brunoscode/laravel-translation-handler](https://github.com/BrunosCode/LaravelTranslationHandler)

## Installation

```bash
composer require brunoscode/twill-translation-handler
```

Run the migrations:

```bash
php artisan migrate
```

Publish the config file:

```bash
php artisan vendor:publish --tag="twill-translation-handler-config"
```

Optionally publish the views to customise them:

```bash
php artisan vendor:publish --tag="twill-translation-handler-views"
```

## Configuration

```php
// config/translation-handler.php

return [
    'keyDelimiter' => '.',          // Separator used in nested translation keys

    'fileNames' => ['messages'],    // PHP file names to sync (without .php extension)
    'locales'   => ['en', 'it'],    // Supported locales

    // Default import/export direction
    'defaultImportFrom' => TranslationOptions::PHP,
    'defaultImportTo'   => TranslationOptions::DB,
    'defaultExportFrom' => TranslationOptions::DB,
    'defaultExportTo'   => TranslationOptions::PHP,

    'phpPath'      => lang_path(),          // Path to PHP language files
    'csvDelimiter' => ';',                  // CSV column delimiter
    'csvFileName'  => 'translations',       // Default CSV file name
    'csvPath'      => storage_path('lang'), // Directory for CSV files
    'jsonPath'     => lang_path(),          // Path to JSON language files
];
```

See [LaravelTranslationHandler](https://github.com/BrunosCode/LaravelTranslationHandler) for the full list of configuration options.

## Usage

Once installed, a **Translations** section will appear in the Twill admin navigation with three pages:

### Keys

Lists all translation keys stored in the database. Each key can be opened to edit its translated value for every configured locale. Translation keys are managed exclusively through import — manual creation is intentionally disabled to keep the database in sync with source files.

### Groups

Lists all translation groups, auto-generated from the key prefixes. Opening a group shows all matching keys in a repeater, allowing batch editing of every locale's value from a single form.

### Import / Export

A tools page with actions to sync translations across sources:

| Action | Description |
|--------|-------------|
| Import from PHP | Reads PHP language files and writes them to the database |
| Export to PHP | Writes all database translations back to PHP language files |
| Import from CSV | Uploads a CSV file and imports all translations into the database |
| Export to CSV | Downloads all database translations as a CSV file |

Per-group CSV import/export is also available directly from each group's edit page.

## Database Structure

| Table | Description |
|-------|-------------|
| `translation_keys` | Translation keys (`key`, `published`) |
| `translation_values` | Per-locale values (`locale`, `value`, `active`) |
| `translation_groups` | Group prefixes (`prefix`, `published`) |

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for recent changes.

## Credits

- [Bruno Magnani](https://github.com/BrunosCode)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
