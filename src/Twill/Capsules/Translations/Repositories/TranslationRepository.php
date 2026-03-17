<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
use BrunosCode\TranslationHandler\Collections\TranslationCollection;
use BrunosCode\TranslationHandler\Data\Translation as TranslationData;
use BrunosCode\TranslationHandler\Data\TranslationOptions;
use BrunosCode\TranslationHandler\Facades\TranslationHandler;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\Translation;
use Illuminate\Support\Facades\DB;

class TranslationRepository extends ModuleRepository
{
    use HandleTranslations;

    public function __construct(Translation $model)
    {
        $this->model = $model;
    }

    public function create($fields)
    {
        $model = null;
        DB::transaction(function () use ($fields, &$model) {
            if (
                !is_array($fields)
                || !is_array($fields['value'])
                || empty($fields['value'])
                || empty($fields['key'])
            ) {
                throw new \Exception('Invalid fields');
            }

            $collection = new TranslationCollection();

            foreach ($fields['value'] as $locale => $value) {
                $collection->addTranslation(new TranslationData($fields['key'], $locale, $value));
            }

            TranslationHandler::set($collection, TranslationOptions::DB);

            $model = Translation::where('key', $fields['key'])->first();
        });

        TranslationHandler::export();

        return $model;
    }

    public function update($id, $fields)
    {
        dd($fields);
        $model = null;
        DB::transaction(function () use ($fields, &$model) {

            if (
                !is_array($fields)
                || !is_array($fields['value'])
                || empty($fields['value'])
                || empty($fields['key'])
            ) {
                throw new \Exception('Invalid fields');
            }

            $collection = new TranslationCollection();

            foreach ($fields['value'] as $locale => $value) {
                $collection->addTranslation(new TranslationData($fields['key'], $value, $locale));
            }

            TranslationHandler::set($collection, TranslationOptions::DB);
            $model = Translation::where('key', $fields['key'])->first();
        });
        
        TranslationHandler::export();

        return $model;
    }
}
