<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;

class Translation extends Model 
{
    use HasTranslation;

    protected $table = 'translation_keys';

    protected $fillable = [
        // 'published',
        'key',
        'value',
    ];
    
    public $translatedAttributes = [
        'value',
        'active',
    ];

    public $translationForeignKey = 'translation_key_id';
}
