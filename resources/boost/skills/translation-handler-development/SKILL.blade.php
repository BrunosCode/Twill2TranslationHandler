{{--
    Live re-export of the base package's skill (brunoscode/laravel-translation-handler).
    See translation-handler-mcp/SKILL.blade.php for the full rationale: Boost only
    discovers skills of DIRECT composer requirements, the base package is transitive
    in a Twill app, and reading the base SKILL.md live avoids duplicating/re-syncing it.
--}}
@php
    $baseSkill = base_path('vendor/brunoscode/laravel-translation-handler/resources/boost/skills/translation-handler-development/SKILL.md');
@endphp
@if (is_file($baseSkill)){!! file_get_contents($baseSkill) !!}@endif
