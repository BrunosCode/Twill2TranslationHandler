<?php

use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models\Translation;
use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models\TranslationGroup;
use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Repositories\TranslationGroupRepository;

beforeEach(function () {
    $this->repository = app(TranslationGroupRepository::class);
});

it('returns translation fields in getFormFields', function () {
    $group = TranslationGroup::create(['prefix' => 'menu']);

    $t1 = Translation::create(['key' => 'menu.home']);
    $t1->translations()->create(['locale' => 'en', 'value' => 'Home', 'active' => true]);
    $t1->translations()->create(['locale' => 'it', 'value' => 'Casa', 'active' => true]);

    $t2 = Translation::create(['key' => 'menu.about']);
    $t2->translations()->create(['locale' => 'en', 'value' => 'About', 'active' => true]);

    $fields = $this->repository->getFormFields($group);

    expect($fields)->toHaveKey('translations')
        ->and($fields['translations'])->toHaveKey('trans_'.$t1->id)
        ->and($fields['translations'])->toHaveKey('trans_'.$t2->id);
});

it('includes translated values in form fields', function () {
    $group = TranslationGroup::create(['prefix' => 'nav']);

    $t = Translation::create(['key' => 'nav.home']);
    $t->translations()->create(['locale' => 'en', 'value' => 'Home', 'active' => true]);
    $t->translations()->create(['locale' => 'it', 'value' => 'Casa', 'active' => true]);

    $fields = $this->repository->getFormFields($group);

    expect($fields['translations']['trans_'.$t->id])->toBe(['en' => 'Home', 'it' => 'Casa']);
});

it('syncs translation values on update', function () {
    $group = TranslationGroup::create(['prefix' => 'sync']);

    $t = Translation::create(['key' => 'sync.hello']);
    $t->translations()->create(['locale' => 'en', 'value' => 'Old', 'active' => true]);
    $t->translations()->create(['locale' => 'it', 'value' => 'Vecchio', 'active' => true]);

    $this->repository->update($group->id, [
        'prefix' => 'sync',
        'published' => true,
        'trans_'.$t->id => ['en' => 'New', 'it' => 'Nuovo'],
    ]);

    $t->refresh();
    $t->load('translations');

    expect($t->translate('en')->value)->toBe('New')
        ->and($t->translate('it')->value)->toBe('Nuovo');
});

it('handles group with no translations gracefully', function () {
    $group = TranslationGroup::create(['prefix' => 'empty']);

    $fields = $this->repository->getFormFields($group);

    expect($fields['translations'] ?? [])->toBeEmpty();
});

it('returns only existing locale values for partial translations', function () {
    $group = TranslationGroup::create(['prefix' => 'partial']);

    $t = Translation::create(['key' => 'partial.key']);
    $t->translations()->create(['locale' => 'en', 'value' => 'English only', 'active' => true]);

    $fields = $this->repository->getFormFields($group);

    expect($fields['translations']['trans_'.$t->id]['en'])->toBe('English only')
        ->and($fields['translations']['trans_'.$t->id])->not->toHaveKey('it');
});
