<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\TranslationGroups\Models;

use A17\Twill\Models\Behaviors\HasTranslation;
use A17\Twill\Models\Model;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\Translation;

class TranslationGroup extends Model 
{
    use HasTranslation;
    
    public $table = 'translation_groups';

    public $fillable = [
        // 'published',
        'prefix',
        'description',
    ];

    public $translatedAttributes = [
        'description',
    ];

    public $translationForeignKey = 'translation_group_id';

    public function getTranslationsCountAttribute()
    {
        if (empty($this->prefix)) {
            return 0;
        }
        return Translation::where('key', 'like', $this->prefix.'%')->count();
    } 

    public function translationsQuery()
    {
        return Translation::query()
            ->when(!empty($this->prefix), fn ($q) => 
                $q->where('key', 'like', $this->prefix.'%')
            );
    }
}
