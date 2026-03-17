<?php

use BrunosCode\TranslationHandler\Collections\TranslationCollection;
use BrunosCode\TranslationHandler\Data\Translation as TranslationData;
use BrunosCode\TranslationHandler\Data\TranslationOptions;
use BrunosCode\TwillTranslationHandler\DatabaseHandler;
use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models\TranslationGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function makeHandler(): DatabaseHandler
{
    return new DatabaseHandler(new TranslationOptions);
}

function makeCollection(array $keys): TranslationCollection
{
    $collection = new TranslationCollection;

    foreach ($keys as $key) {
        $collection->push(new TranslationData($key, 'en', 'value'));
    }

    return $collection;
}

it('creates groups from translation key prefixes', function () {
    $handler = makeHandler();
    $db = app('db')->connection();

    $translations = makeCollection(['test.menu.home', 'test.menu.about']);

    $handler->handleInsert($db, $translations, 'test');

    expect(TranslationGroup::count())->toBe(2)
        ->and(TranslationGroup::where('prefix', 'test')->exists())->toBeTrue()
        ->and(TranslationGroup::where('prefix', 'test.menu')->exists())->toBeTrue();
});

it('creates hierarchical groups for deeply nested keys', function () {
    $handler = makeHandler();
    $db = app('db')->connection();

    $translations = makeCollection(['file.key1.key2.key3']);

    $handler->handleInsert($db, $translations, 'file');

    expect(TranslationGroup::count())->toBe(3)
        ->and(TranslationGroup::where('prefix', 'file')->exists())->toBeTrue()
        ->and(TranslationGroup::where('prefix', 'file.key1')->exists())->toBeTrue()
        ->and(TranslationGroup::where('prefix', 'file.key1.key2')->exists())->toBeTrue();
});

it('does not create duplicate groups', function () {
    $handler = makeHandler();
    $db = app('db')->connection();

    $translations = makeCollection([
        'test.menu.home',
        'test.menu.about',
        'test.footer.copyright',
    ]);

    $handler->handleInsert($db, $translations, 'test');

    expect(TranslationGroup::where('prefix', 'test')->count())->toBe(1);
});

it('does not create a group for single-part keys', function () {
    $handler = makeHandler();
    $db = app('db')->connection();

    $translations = makeCollection(['hello']);

    $handler->handleInsert($db, $translations, null);

    expect(TranslationGroup::count())->toBe(0);
});

it('handles multiple files creating shared prefixes', function () {
    $handler = makeHandler();
    $db = app('db')->connection();

    $batch1 = makeCollection(['app.title', 'app.description']);
    $handler->handleInsert($db, $batch1, 'app');

    $batch2 = makeCollection(['app.footer.text']);
    $handler->handleInsert($db, $batch2, 'app');

    expect(TranslationGroup::where('prefix', 'app')->count())->toBe(1)
        ->and(TranslationGroup::where('prefix', 'app.footer')->exists())->toBeTrue();
});
