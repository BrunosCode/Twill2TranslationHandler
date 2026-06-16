{{--
    Live re-export of the base package's Boost guideline.

    Why: Laravel Boost only discovers guidelines/skills of packages listed as a
    DIRECT requirement in the consuming app's composer.json (see
    Laravel\Boost\Support\Composer::packages()). In a Twill app, the direct
    dependency is brunoscode/twill-translation-handler; brunoscode/laravel-translation-handler
    comes in transitively and is therefore never discovered by `boost:install`.

    To avoid losing the base guideline AND to avoid duplicating its content here
    (which would mean re-syncing on every base-package edit), we read the base
    guideline live at install time. `boost:install` renders this file as Blade,
    so we can pull the installed base package's guideline from vendor/ and emit it.
    A `composer update` + re-run of `boost:install` then picks up the latest base
    content automatically — no change required in this package.
--}}
@php
    $baseGuideline = base_path('vendor/brunoscode/laravel-translation-handler/resources/boost/guidelines/core.blade.php');
@endphp
@if (is_file($baseGuideline))
{!! \Illuminate\Support\Facades\Blade::render(file_get_contents($baseGuideline), ['assist' => $assist]) !!}
@endif

### Editing translations from the Twill admin (brunoscode/twill-translation-handler)

This app also installs **twill-translation-handler**, a Twill 3 layer on top of the base package. It adds a Twill admin module — **Translations** and **Translation Groups** — plus CSV import/export tools, so editors manage translation content directly in the CMS. Under the hood the admin works against the `db` format described above.

- **Human editors** use the Twill admin (Translations / Translation Groups, and the CSV tools) to add and edit translations in `db`.
- **AI agents** must still go through the base package's Boost MCP tools / `translation-handler:*` Artisan commands — never hand-edit `lang/*.php` or `lang/*.json`. The `db`-then-`sync` workflow is unchanged: edit in `db`, then `sync-translations-tool` (`db` → `php_file` / `json_file`) once at the end, and finish with `check-translations-tool`.
- Because the Twill admin writes to `db`, after editors finish a round of changes someone (or an agent) still needs to `sync` `db` into the file formats the app/frontend consumes.
