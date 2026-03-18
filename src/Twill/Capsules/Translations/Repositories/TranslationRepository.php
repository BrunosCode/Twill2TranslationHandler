<?php

namespace BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use BrunosCode\TranslationHandler\Data\TranslationOptions;
use BrunosCode\TranslationHandler\Facades\TranslationHandler;
use BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models\Translation;

class TranslationRepository extends ModuleRepository
{
    use HandleTranslations;

    public function __construct(Translation $model)
    {
        $this->model = $model;
    }

    public function filter($query, array $scopes = [])
    {
        if (! empty($scopes['search'])) {
            $search = $scopes['search'];
            $query->where(function ($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                    ->orWhereHas('translations', function ($q) use ($search) {
                        $q->where('value', 'like', "%{$search}%");
                    });
            });
            unset($scopes['search']);
        }

        return parent::filter($query, $scopes);
    }

    public function update($id, $fields)
    {
        unset($fields['allow_empty']);

        return parent::update($id, $fields);
    }

    public function afterSave($object, $fields)
    {
        try {
            $delimiter = config('translation-handler.keyDelimiter', '.');
            $prefix = explode($delimiter, $object->key)[0];

            TranslationHandler::setOption('fileNames', [$prefix])
                ->export(TranslationOptions::DB, TranslationOptions::PHP, true);

            TranslationHandler::resetOptions();
        } catch (\Throwable $e) {
            TranslationHandler::resetOptions();
        }

        parent::afterSave($object, $fields);
    }
}
