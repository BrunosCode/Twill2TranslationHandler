{{--
    Live re-export of the base package's skill (brunoscode/laravel-translation-handler).

    Boost only discovers skills of DIRECT composer requirements; in a Twill app the
    base package is transitive, so its skills would be lost. Rather than copy the
    SKILL.md here (and have to re-sync on every base edit), we read it live at
    install time. The base SKILL.md is plain Markdown — including its YAML
    frontmatter (name + description) — so we emit it verbatim and inherit the
    frontmatter unchanged. `boost:install` renders this Blade file, then writes the
    result as SKILL.md into the agent's skills directory.

    A `composer update` of the base package + re-run of `boost:install` picks up the
    latest content automatically; this wrapper never needs editing unless the base
    package adds or renames a whole skill (those directories must exist physically here).
--}}
@php
    $baseSkill = base_path('vendor/brunoscode/laravel-translation-handler/resources/boost/skills/translation-handler-mcp/SKILL.md');
@endphp
@if (is_file($baseSkill)){!! file_get_contents($baseSkill) !!}@endif
