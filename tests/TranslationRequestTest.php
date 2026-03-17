<?php

use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Http\Requests\TranslationRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires key on create', function () {
    $request = new TranslationRequest;
    $rules = $request->rulesForCreate();

    expect($rules)->toHaveKey('key')
        ->and($rules['key'])->toContain('required');
});

it('requires unique key on create', function () {
    $request = new TranslationRequest;
    $rules = $request->rulesForCreate();

    expect($rules['key'])->toContain('unique:translation_keys,key');
});

it('requires value for each active locale on update', function () {
    config()->set('translatable.locales', ['en', 'it']);

    $request = TranslationRequest::create('/test', 'PUT', [
        'languages' => [
            ['value' => 'en', 'published' => true],
            ['value' => 'it', 'published' => true],
        ],
    ]);
    $rules = $request->rulesForUpdate();

    expect($rules)->toHaveKey('value.en')
        ->and($rules)->toHaveKey('value.it');

    expect($rules['value.en'])->toContain('required');
    expect($rules['value.it'])->toContain('required');
});

it('makes value nullable for inactive locales', function () {
    config()->set('translatable.locales', ['en', 'it']);

    $request = TranslationRequest::create('/test', 'PUT', [
        'languages' => [
            ['value' => 'en', 'published' => true],
            ['value' => 'it', 'published' => false],
        ],
    ]);
    $rules = $request->rulesForUpdate();

    expect($rules['value.en'])->toContain('required');
    expect($rules['value.it'])->toContain('nullable')
        ->and($rules['value.it'])->not->toContain('required');
});
