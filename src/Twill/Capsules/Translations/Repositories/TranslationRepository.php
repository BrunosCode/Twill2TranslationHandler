<?php

namespace BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Repositories;

use A17\Twill\Repositories\Behaviors\HandleTranslations;
use A17\Twill\Repositories\ModuleRepository;
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
}
