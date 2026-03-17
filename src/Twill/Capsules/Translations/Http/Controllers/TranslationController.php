<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Http\Controllers;

use A17\Twill\Http\Controllers\Admin\ModuleController as BaseModuleController;

class TranslationController extends BaseModuleController
{
    protected $moduleName = 'translations';

    protected $titleColumnKey = 'key';

    protected $indexOptions = [
        'create' => true,
        'edit' => true,
        'publish' => false,
        'bulkPublish' => false,
        'feature' => false,
        'bulkFeature' => false,
        'restore' => true,
        'bulkRestore' => true,
        'forceDelete' => true,
        'bulkForceDelete' => false,
        'delete' => true,
        'duplicate' => false,
        'bulkDelete' => true,
        'reorder' => false,
        'permalink' => false,
        'bulkEdit' => true,
        'editInModal' => false,
        'skipCreateModal' => false,
    ];

    protected $indexColumns = [
        'key' => [ // field column
            'title' => 'Key',
            'field' => 'key',
            'sort' => true, // column is sortable
        ],
        'value' => [ // field column
            'title' => 'Value',
            'field' => 'value',
            'sort' => true, // column is sortable
        ],
    ];
}
