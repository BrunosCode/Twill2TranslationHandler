<?php

namespace BrunosCode\TwillTranslationHandler\Twill\Capsules\Translations\Models;

use A17\Twill\Models\Model;

class TranslationTranslation extends Model
{
    protected $table = 'translation_values';

    protected $fillable = [
        'translation_key_id',
        'locale',
        'value',
        'active',
    ];

    protected $baseModuleModel = Translation::class;
}
