<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\TranslationGroups\Http\Controllers;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

class TranslationGroupController extends BaseModuleController
{
    protected $moduleName = 'translationGroups';

    protected $titleColumnKey = 'prefix';

    protected $indexOptions = [
        'create' => false,
        'edit' => true,
        'publish' => false,
        'bulkPublish' => false,
        'feature' => false,
        'bulkFeature' => false,
        'restore' => false,
        'bulkRestore' => false,
        'forceDelete' => false,
        'bulkForceDelete' => false,
        'delete' => false,
        'duplicate' => false,
        'bulkDelete' => false,
        'reorder' => false,
        'permalink' => false,
        'bulkEdit' => false,
        'editInModal' => false,
        'skipCreateModal' => false,
    ];

    protected $indexColumns = [
        'prefix' => [ // field column
            'title' => 'Prefix',
            'field' => 'prefix',
            'sort' => true, // column is sortable
        ],
        'translations_count' => [ // field column
            'title' => 'Translations Count',
            'field' => 'translations_count',
        ],
    ];
}
