<?php

return [
    'translations' => [
        'title' => 'Translations',
        'route' => 'admin.translations.index',
        'primary_navigation' => [
            'translations' => [
                'title' => 'Keys',
                'module' => true,
            ],
            'translationTools' => [
                'title' => 'Import / Export',
                'route' => 'admin.translationTools.index',
                'params' => [],
            ],
        ],
    ],
];
