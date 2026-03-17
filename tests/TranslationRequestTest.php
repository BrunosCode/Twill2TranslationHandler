<?php

use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Http\Requests\TranslationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires key on create', function () {
    $request = new TranslationRequest();
    $rules = $request->rulesForCreate();

    expect($rules)->toHaveKey('key')
        ->and($rules['key'])->toContain('required');
});

it('requires unique key on create', function () {
    $request = new TranslationRequest();
    $rules = $request->rulesForCreate();

    expect($rules['key'])->toContain('unique:translation_keys,key');
});

it('requires value for each configured locale on update', function () {
    config()->set('translation-handler.locales', ['en', 'it']);

    $request = new TranslationRequest();
    $rules = $request->rulesForUpdate();

    expect($rules)->toHaveKey('translations.en.value')
        ->and($rules)->toHaveKey('translations.it.value')
        ->and($rules['translations.en.value'])->toBe('required|string')
        ->and($rules['translations.it.value'])->toBe('required|string');
});

it('adapts validation to configured locales', function () {
    config()->set('translation-handler.locales', ['en', 'fr', 'de']);

    $request = new TranslationRequest();
    $rules = $request->rulesForUpdate();

    expect($rules)->toHaveCount(3)
        ->and($rules)->toHaveKey('translations.en.value')
        ->and($rules)->toHaveKey('translations.fr.value')
        ->and($rules)->toHaveKey('translations.de.value');
});
