<?php

use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\Translation;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\TranslationGroup;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Repositories\TranslationGroupRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = app(TranslationGroupRepository::class);
});

it('returns repeater data in getFormFields', function () {
    $group = TranslationGroup::create(['prefix' => 'menu']);

    $t1 = Translation::create(['key' => 'menu.home']);
    $t1->translations()->create(['locale' => 'en', 'value' => 'Home', 'active' => true]);
    $t1->translations()->create(['locale' => 'it', 'value' => 'Casa', 'active' => true]);

    $t2 = Translation::create(['key' => 'menu.about']);
    $t2->translations()->create(['locale' => 'en', 'value' => 'About', 'active' => true]);

    $fields = $this->repository->getFormFields($group);

    expect($fields)->toHaveKey('repeaters')
        ->and($fields['repeaters'])->toHaveKey('translation_item')
        ->and($fields['repeaters']['translation_item'])->toHaveCount(2);
});

it('includes translation keys in repeater fields', function () {
    $group = TranslationGroup::create(['prefix' => 'nav']);

    Translation::create(['key' => 'nav.home']);

    $fields = $this->repository->getFormFields($group);
    $repeaterFields = $fields['repeaterFields']['translation_item'];

    $keyField = collect($repeaterFields)->first(fn ($f) => str_contains($f['name'], '[key]'));

    expect($keyField['value'])->toBe('nav.home');
});

it('includes translated values in repeater fields', function () {
    $group = TranslationGroup::create(['prefix' => 'nav']);

    $t = Translation::create(['key' => 'nav.home']);
    $t->translations()->create(['locale' => 'en', 'value' => 'Home', 'active' => true]);
    $t->translations()->create(['locale' => 'it', 'value' => 'Casa', 'active' => true]);

    $fields = $this->repository->getFormFields($group);
    $repeaterFields = $fields['repeaterFields']['translation_item'];

    $valueField = collect($repeaterFields)->first(fn ($f) => str_contains($f['name'], '[value]'));

    expect($valueField['value'])->toBe(['en' => 'Home', 'it' => 'Casa']);
});

it('syncs translation values on update', function () {
    $group = TranslationGroup::create(['prefix' => 'sync']);

    $t = Translation::create(['key' => 'sync.hello']);
    $t->translations()->create(['locale' => 'en', 'value' => 'Old', 'active' => true]);
    $t->translations()->create(['locale' => 'it', 'value' => 'Vecchio', 'active' => true]);

    $this->repository->update($group->id, [
        'prefix' => 'sync',
        'published' => true,
        'languages' => [
            ['shortlabel' => 'EN', 'label' => 'English', 'value' => 'en', 'published' => true],
            ['shortlabel' => 'IT', 'label' => 'Italian', 'value' => 'it', 'published' => true],
        ],
        'repeaters' => [
            'translation_item' => [
                [
                    'id' => 'translation_item-'.$t->id,
                    'key' => 'sync.hello',
                    'value' => ['en' => 'New', 'it' => 'Nuovo'],
                ],
            ],
        ],
    ]);

    $t->refresh();
    $t->load('translations');

    expect($t->translate('en')->value)->toBe('New')
        ->and($t->translate('it')->value)->toBe('Nuovo');
});

it('handles empty repeater gracefully', function () {
    $group = TranslationGroup::create(['prefix' => 'empty']);

    $fields = $this->repository->getFormFields($group);

    expect($fields['repeaters']['translation_item'])->toBeEmpty()
        ->and($fields['repeaterFields']['translation_item'])->toBeEmpty();
});

it('returns empty value for missing locale translations', function () {
    $group = TranslationGroup::create(['prefix' => 'partial']);

    $t = Translation::create(['key' => 'partial.key']);
    $t->translations()->create(['locale' => 'en', 'value' => 'English only', 'active' => true]);

    $fields = $this->repository->getFormFields($group);
    $repeaterFields = $fields['repeaterFields']['translation_item'];

    $valueField = collect($repeaterFields)->first(fn ($f) => str_contains($f['name'], '[value]'));

    expect($valueField['value']['en'])->toBe('English only')
        ->and($valueField['value']['it'])->toBe('');
});
