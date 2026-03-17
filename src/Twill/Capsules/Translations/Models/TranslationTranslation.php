<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models;

use A17\Twill\Models\Model;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\Translation;

class TranslationTranslation extends Model
{
    protected $baseModuleModel = Translation::class;

    protected $table = 'translation_values';
}
