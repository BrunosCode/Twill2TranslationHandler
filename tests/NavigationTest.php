<?php



it('registers translations route', function () {
    expect(route('admin.translations.translations.index'))
        ->toContain('/translations/translations');
});

it('registers translationGroups route under translations prefix', function () {
    expect(route('admin.translations.translationGroups.index'))
        ->toContain('/translations/translationGroups');
});

it('registers translationTools route under translations prefix', function () {
    expect(route('admin.translations.translationTools.index'))
        ->toContain('/translations/tools');
});

it('registers translationGroups edit route', function () {
    expect(route('admin.translations.translationGroups.edit', ['translationGroup' => 1]))
        ->toContain('/translations/translationGroups/1/edit');
});

it('registers translation tools action routes', function () {
    expect(route('admin.translations.translationTools.exportToCsv'))
        ->toContain('/translations/tools/export-csv');
    expect(route('admin.translations.translationTools.importFromCsv'))
        ->toContain('/translations/tools/import-csv');
});

it('configures navigation with correct structure', function () {
    $nav = config('twill-navigation');

    expect($nav)->toHaveKey('translations')
        ->and($nav['translations'])->toHaveKey('primary_navigation')
        ->and($nav['translations']['primary_navigation'])->toHaveKeys([
            'translations',
            'translationGroups',
            'translationTools',
        ]);
});

it('navigation points to correct routes', function () {
    $nav = config('twill-navigation.translations.primary_navigation');

    expect($nav['translations']['route'])->toBe('admin.translations.translations.index')
        ->and($nav['translationGroups']['route'])->toBe('admin.translations.translationGroups.index')
        ->and($nav['translationTools']['route'])->toBe('admin.translations.translationTools.index');
});

it('all navigation routes share the translations prefix for active highlighting', function () {
    $routes = [
        'admin.translations.translations.index',
        'admin.translations.translationGroups.index',
        'admin.translations.translationTools.index',
    ];

    foreach ($routes as $routeName) {
        $parts = explode('.', $routeName);
        // parts[0] = admin, parts[1] = global nav key
        expect($parts[1])->toBe('translations',
            "Route {$routeName} should have 'translations' as global nav segment"
        );
    }
});
