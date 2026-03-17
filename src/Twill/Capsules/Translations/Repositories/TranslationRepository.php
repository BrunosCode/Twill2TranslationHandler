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
}
