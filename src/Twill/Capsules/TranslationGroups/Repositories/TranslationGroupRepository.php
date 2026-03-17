<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\TranslationGroups\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\TranslationGroups\Models\TranslationGroup;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\Translation;

class TranslationGroupRepository extends ModuleRepository
{
    use HandleTranslations;

    public function __construct(TranslationGroup $model)
    {
        $this->model = $model;
    }

    public function getFormFields($object)
    {
        assert($object instanceof TranslationGroup);
        
        $fields = parent::getFormFields($object);

        // $fields = $this->getTranslationsFields($object);

        return $fields;
    }

    public function getTranslationsFields(TranslationGroup $object, array $fields = []): array
    {
        $translations = $object->translationsQuery()->with('translations')->get();

        foreach ($translations as $translation) {
            $values = $translation->translations->pluck('value', 'locale')->toArray();
            $fields[] = [
                'key' => $translation->key,
                'translations' => [
                    'value' => $translation->translations->mapWithKeys(function ($item, $key) {
                        return [$item->locale => $item->value];
                    })->toArray(),
                ]
            ];
        }

        return $fields;
    }

}
