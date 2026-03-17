<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\TranslationGroups\Models;

use A17\Twill\Models\Model;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\TranslationGroups\Models\TranslationGroup;

class TranslationGroupTranslation extends Model
{
    protected $baseModuleModel = TranslationGroup::class;

    protected $table = 'translation_group_locales';
}
