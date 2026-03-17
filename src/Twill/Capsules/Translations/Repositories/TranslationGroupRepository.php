<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Repositories;

use A17\Twill\Repositories\ModuleRepository;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\Translation;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\TranslationGroup;

class TranslationGroupRepository extends ModuleRepository
{
    public function __construct(TranslationGroup $model)
    {
        $this->model = $model;
    }

    public function getFormFields($object)
    {
        $fields = parent::getFormFields($object);

        /** @var TranslationGroup $object */
        $translations = $object->getTranslationsQuery()
            ->with('translations')
            ->get();

        $locales = config('translation-handler.locales', ['en']);

        $repeaterItems = [];
        $repeaterFields = [];

        foreach ($translations as $translation) {
            $itemId = 'translation_item-'.$translation->id;

            $repeaterItems[] = [
                'id' => $itemId,
                'type' => 'translation_item',
                'title' => $translation->key,
            ];

            $repeaterFields[] = [
                'name' => "blocks[{$itemId}][key]",
                'value' => $translation->key,
            ];

            $valueByLocale = [];
            foreach ($locales as $locale) {
                $tv = $translation->translations->firstWhere('locale', $locale);
                $valueByLocale[$locale] = $tv->value ?? '';
            }

            $repeaterFields[] = [
                'name' => "blocks[{$itemId}][value]",
                'value' => $valueByLocale,
            ];
        }

        $fields['repeaters']['translation_item'] = $repeaterItems;
        $fields['repeaterFields']['translation_item'] = $repeaterFields;

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
