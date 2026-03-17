<?php

namespace BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Repositories;

use A17\Twill\Repositories\Behaviors\HandleRepeaters;
use A17\Twill\Repositories\ModuleRepository;
use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models\Translation;
use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models\TranslationGroup;

class TranslationGroupRepository extends ModuleRepository
{
    use HandleRepeaters;

    public function __construct(TranslationGroup $model)
    {
        $this->model = $model;
    }

    public function getFormFields($object)
    {
        $fields = parent::getFormFields($object);

        $fields = $this->getFormFieldsForRepeater(
            $object,
            $fields,
            'translation_items',
            /** @phpstan-ignore argument.type */
            TranslationRepository::class,
            'translation_item'
        );

        return $fields;
    }

    public function update($id, $fields)
    {
        $this->syncTranslationValues($fields);

        unset($fields['repeaters'], $fields['blocks']);

        return parent::update($id, $fields);
    }

    protected function syncTranslationValues(array $fields): void
    {
        $repeaterItems = $fields['repeaters']['translation_item'] ?? [];
        $locales = config('translation-handler.locales', ['en']);

        foreach ($repeaterItems as $item) {
            $key = $item['key'] ?? null;

            if (! $key) {
                continue;
            }

            $translation = Translation::where('key', $key)->first();

            if (! $translation) {
                continue;
            }

            foreach ($locales as $locale) {
                $value = $item['value'][$locale] ?? '';

                $translation->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['value' => $value, 'active' => true]
                );
            }
        }
    }
}
