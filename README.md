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
- **Import / Export** — Sync translations between the database and CSV files:
  - Import from PHP files to populate the database
  - Export all translations to CSV or upload a CSV to import
  - Export a single group as CSV directly from its edit page
- **Automatic group syncing** — When translations are imported, the `DatabaseHandler` automatically creates all intermediate group levels (e.g. importing `messages.validation.required` creates both `messages` and `messages.validation` groups).
- **Auto-sync to PHP files** — Saving a translation key or a group automatically exports the affected PHP language file, keeping the filesystem always in sync with the database.

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

## Navigation

The package registers a **Translations** entry in the Twill admin sidebar automatically — no extra configuration needed. It provides three sub-pages:

- **Keys** — browse and edit individual translation keys
- **Groups** — edit translations grouped by prefix
- **Import / Export** — sync with PHP files or CSV

### Controlling the position in the sidebar

Twill orders navigation items based on the `config/twill-navigation.php` file. To place the Translations entry at a specific position, add it explicitly in your app's navigation config:

```php
// config/twill-navigation.php

return [
    'dashboard' => [
        'title'   => 'Dashboard',
        'route'   => 'admin.dashboard',
    ],

    // Add this block wherever you want it to appear
    'translations' => [
        'title' => 'Translations',
        'route' => 'admin.translations.translations.index',
        'primary_navigation' => [
            'translations' => [
                'title' => 'Keys',
                'route' => 'admin.translations.translations.index',
            ],
            'translationGroups' => [
                'title' => 'Groups',
                'route' => 'admin.translations.translationGroups.index',
            ],
            'translationTools' => [
                'title' => 'Import / Export',
                'route' => 'admin.translations.translationTools.index',
            ],
        ],
    ],

    'pages' => [
        'title' => 'Pages',
        'route' => 'admin.pages.index',
    ],

    // ...
];
```

The package always fills in the navigation structure for the `translations` key at boot. Defining the key in your config ahead of time only controls its **position** in the sidebar — PHP arrays preserve the insertion order of existing keys when they are updated.

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

A tools page with actions to sync translations:

| Action | Description |
|--------|-------------|
| Import from CSV | Uploads a CSV file, imports all translations into the database, and writes the PHP language files |
| Export to CSV | Downloads all database translations as a CSV file |

Per-group CSV export is also available directly from each group's edit page.

#### CSV import rules

- The file does not need to contain all keys — only the keys present in the file will be updated.
- All locale columns must be present in the file header.
- Empty values will overwrite the existing translation with an empty string.

> PHP language files are kept in sync automatically: every import (CSV via the tools page or per-group) writes the affected PHP files immediately after updating the database. Saving a single key or group from the edit form also triggers the same export.

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
