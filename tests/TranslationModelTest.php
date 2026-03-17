<?php

use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\Translation;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\TranslationTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a translation key', function () {
    $translation = Translation::create([
        'key' => 'test.hello',
        'published' => true,
    ]);

    expect($translation)->toBeInstanceOf(Translation::class)
        ->and($translation->key)->toBe('test.hello')
        ->and($translation->published)->toBeTrue();
});

it('can create a translation with locale values', function () {
    $translation = Translation::create([
        'key' => 'test.greeting',
        'published' => true,
    ]);

    $translation->translations()->create([
        'locale' => 'en',
        'value' => 'Hello',
        'active' => true,
    ]);

    $translation->translations()->create([
        'locale' => 'it',
        'value' => 'Ciao',
        'active' => true,
    ]);

    $translation->load('translations');

    expect($translation->translations)->toHaveCount(2)
        ->and($translation->translate('en')->value)->toBe('Hello')
        ->and($translation->translate('it')->value)->toBe('Ciao');
});

it('has published default to true', function () {
    $translation = Translation::create(['key' => 'test.default']);
    $translation->refresh();

    expect((bool) $translation->published)->toBeTrue();
});

it('has active default to true on translation values', function () {
    $translation = Translation::create(['key' => 'test.active']);

    $value = $translation->translations()->create([
        'locale' => 'en',
        'value' => 'Test',
    ]);

    $value->refresh();

    expect((bool) $value->active)->toBeTrue();
});

it('enforces unique keys', function () {
    Translation::create(['key' => 'test.unique']);

    Translation::create(['key' => 'test.unique']);
})->throws(\Illuminate\Database\QueryException::class);

it('enforces unique locale per key', function () {
    $translation = Translation::create(['key' => 'test.locale']);

    $translation->translations()->create([
        'locale' => 'en',
        'value' => 'First',
    ]);

    $translation->translations()->create([
        'locale' => 'en',
        'value' => 'Duplicate',
    ]);
})->throws(\Illuminate\Database\QueryException::class);

it('supports soft deletes', function () {
    $translation = Translation::create(['key' => 'test.soft']);
    $translation->delete();

    expect(Translation::count())->toBe(0)
        ->and(Translation::withTrashed()->count())->toBe(1);
});

it('cascades delete to translation values', function () {
    $translation = Translation::create(['key' => 'test.cascade']);

    $translation->translations()->create([
        'locale' => 'en',
        'value' => 'Will be deleted',
    ]);

    $translation->forceDelete();

    expect(TranslationTranslation::withTrashed()->count())->toBe(0);
});

it('uses translation_keys as table name', function () {
    $translation = new Translation();

    expect($translation->getTable())->toBe('translation_keys');
});

it('uses translation_key_id as foreign key', function () {
    $translation = new Translation();

    expect($translation->getTranslationRelationKey())->toBe('translation_key_id');
});
