<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models;

use A17\Twill\Models\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $prefix
 * @property bool $published
 * @property-read int $translations_count
 */
class TranslationGroup extends Model
{
    protected $table = 'translation_groups';

    protected $fillable = [
        'prefix',
        'published',
    ];

    /**
     * @return Builder<Translation>
     */
    public function getTranslationsQuery(): Builder
    {
        $delimiter = config('translation-handler.keyDelimiter', '.');

        return Translation::where('key', 'like', $this->prefix.$delimiter.'%')
            ->orderBy('key');
    }

    public function getTranslationsCountAttribute(): int
    {
        return $this->getTranslationsQuery()->count();
    }
}
