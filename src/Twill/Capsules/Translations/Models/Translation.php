<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;

/**
 * @property string $key
 * @property bool $published
 */
class Translation extends Model
{
    use HasTranslation;

    protected $table = 'translation_keys';

    public $translationModel = TranslationTranslation::class;

    public $translationForeignKey = 'translation_key_id';

    protected $fillable = [
        'key',
        'published',
    ];

    public $translatedAttributes = [
        'value',
        'active',
    ];
}
