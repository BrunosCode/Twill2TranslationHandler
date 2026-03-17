<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Http\Controllers;

use A17\Twill\Http\Controllers\Admin\ModuleController;
use BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Models\TranslationGroup;

class TranslationGroupController extends ModuleController
{
    protected $moduleName = 'translationGroups';

    protected $titleColumnKey = 'prefix';

    protected $titleFormKey = 'prefix';

    protected $disableEditor = true;

    protected $indexColumns = [
        'prefix' => [
            'title' => 'Prefix',
            'field' => 'prefix',
            'sort' => true,
        ],
        'translations_count' => [
            'title' => 'Translations',
            'field' => 'translations_count',
            'present' => true,
        ],
    ];

    protected $defaultOrders = ['prefix' => 'asc'];

    protected $indexOptions = [
        'create' => false,
        'publish' => false,
        'bulkPublish' => false,
        'feature' => false,
        'bulkFeature' => false,
        'delete' => false,
        'bulkDelete' => false,
    ];

    protected function indexItemData($item)
    {
        /** @var TranslationGroup $item */
        return [
            'translations_count' => $item->translations_count,
        ];
    }
}
