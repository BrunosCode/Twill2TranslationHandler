# Changelog

All notable changes to `TwillTranslationHandler` will be documented in this file.

## v1.0.0 - 2026-03-18

First stable release of **Twill Translation Handler** — a Twill 2 capsule for managing Laravel translations directly from the admin panel.

### Features

- **Translation keys** — browse and edit all translation keys stored in the database, with per-locale values editable inline.
- **Groups** — translations are automatically organized by prefix (e.g. `messages`, `messages.validation`). Intermediate groups are created automatically on import. Each group can be edited as a single form with all matching keys and their locale values exposed directly as textareas.
- **Search** — filter translations by key and value in both the keys list and the groups list.
- **Auto-sync to PHP files** — saving a translation key or a group automatically writes the corresponding PHP language file, keeping the filesystem always in sync with the database.
- **CSV import / export** — upload a CSV to import translations into the database (and PHP files), or download all translations as CSV from the tools page.
- **Per-group CSV export** — download a filtered CSV for a single group directly from its edit page (GET request, no nested form).
- **Allow empty values flag** — each group form exposes a checkbox to bypass validation and allow saving empty translation values.
- **Validation with translation keys** — form validation errors on the group page display the actual translation key (`messages.welcome (it) is required`) instead of the internal field name.
- **Automatic navigation** — a *Translations* entry with three sub-pages (Keys, Groups, Import / Export) is registered in the Twill sidebar automatically at boot. Position in the sidebar can be controlled via a placeholder in `config/twill-navigation.php`.

### Requirements

- PHP 8.2 or 8.3
- Laravel 10
- Twill 2.x
- [brunoscode/laravel-translation-handler](https://github.com/BrunosCode/LaravelTranslationHandler) ^1.0

> Support for Twill 3 and Laravel 11 is planned for a future release.
**Full Changelog**: https://github.com/BrunosCode/TwillTranslationHandler/compare/v0.2...v1
