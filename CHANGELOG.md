# Changelog

All notable changes to `TwillTranslationHandler` will be documented in this file.

## v2.0.1 - 2026-03-31

### Bug fixes

- **`CannotCombineNavigationBuilderWithLegacyConfig` exception on install.** Apps using the `TwillNavigation` builder API would crash at boot because the package unconditionally registered the Translations entries via the legacy `twill-navigation` config array. Both APIs cannot coexist in Twill 3.

### New config flag: `legacy-twill-navigation`

```php
// config/translation-handler.php
'legacy-twill-navigation' => true, // default — behaviour unchanged from v2.0

```
Set to `false` if your app uses the `TwillNavigation` builder and register the navigation entries yourself. See the [Navigation](README.md#navigation) section in the README for both approaches.

### Other changes

- Package migrations are now auto-loaded via `loadMigrationsFrom()` — no manual step required after `composer require`.
- Tests added for both flag states (`legacy-twill-navigation = true/false`), verifying that the `twill-navigation` config is populated or left untouched accordingly, and that routes remain registered in both cases.

## v2.0.0 — Twill 3 + Laravel 11 - 2026-03-19

> **Twill 2 / Laravel 10 users:** stay on the [`v1.x`](https://github.com/brunoscode/twill-translation-handler/tree/v1.x) releases.

### Breaking changes

| Requirement | v1.x | v2.0 |
|---|---|---|
| PHP | 8.1+ | **8.2+** |
| Laravel | 10 | **11** |
| Twill | 2.x | **3.x** |
| laravel-translation-handler | ^1.0 | **^2.0** |

### Twill 3 compatibility

- All repository and controller methods updated to match Twill 3 contracts: `TwillModelContract` return type on `update`, strict signatures on `afterSave`, `filter`, `getFormFields`, `indexItemData`.
- Route name prefix is now read from `config('twill.admin_route_name_prefix', 'admin.')` everywhere — works with both Twill 3's default `twill.` prefix and custom values.

### Bug fixes

- **`importGroupCsv` ignored the selected CSV delimiter.** Uploading a group CSV with `,` or `⇥` as delimiter always fell back to `;` and failed. The delimiter is now forwarded correctly from the request.
- **Runtime `setOption` overrides ignored on Laravel 11.** Laravel 11's IoC container removed automatic conversion of positional parameters to named ones, causing `getCsvHandler()` / `getPhpHandler()` etc. to resolve a fresh `TranslationOptions` from config instead of using the modified one. Fixed in `laravel-translation-handler` v2.0.2 (named parameters now used explicitly).

### Tests

Rewrote the test infrastructure to be fully compatible with Laravel 11 + SQLite (Twill's default migrations include a cascade migration that fails on SQLite in Laravel 11 — all tables are now defined inline in `TestCase`).

Added `TranslationToolsControllerTest` with 14 cases covering:

- `importFromCsv` and `importGroupCsv` with `;`, `,` and `⇥` delimiters
- `exportToCsv` and `exportGroupCsv` download responses
- Error paths: mismatched delimiter, missing file, non-existent group

> The 404 test for unknown group uses a model-level assertion rather than an HTTP request to avoid Twill attempting to render its error view (which requires compiled assets not present in the test pipeline).

### Upgrade from v1.x

```bash
composer require brunoscode/twill-translation-handler:^2.0 area17/twill:^3.0 laravel/framework:^11.0
php artisan migrate


```
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
