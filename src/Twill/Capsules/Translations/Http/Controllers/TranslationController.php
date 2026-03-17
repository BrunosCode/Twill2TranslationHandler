<?php

namespace BrunosCode\Twill2TranslationHandler\Twill\Capsules\Translations\Http\Controllers;

use A17\Twill\Http\Controllers\Admin\ModuleController;

class TranslationController extends ModuleController
{
    protected $moduleName = 'translations';

    protected $titleColumnKey = 'key';

    protected $titleFormKey = 'key';

    protected $indexColumns = [
        'key' => [
            'title' => 'Key',
            'field' => 'key',
            'sort' => true,
        ],
    ];

    protected $defaultOrders = ['key' => 'asc'];

    protected $disableEditor = true;

    protected $indexOptions = [
        'create' => false,
        'delete' => false,
        'publish' => false,
        'bulkPublish' => false,
        'bulkDelete' => false,
        'feature' => false,
        'bulkFeature' => false,
    ];
}
