<?php

// config for BrunosCode/TranslationHandler

return [
    'keyDelimiter' => '.',

    'fileNames' => ['test'],
    'locales' => ['en', 'it'],

    'defaultImportFrom' => \BrunosCode\TranslationHandler\Data\TranslationOptions::PHP,
    'defaultImportTo' => \BrunosCode\TranslationHandler\Data\TranslationOptions::DB,
    'defaultExportFrom' => \BrunosCode\TranslationHandler\Data\TranslationOptions::DB,
    'defaultExportTo' => \BrunosCode\TranslationHandler\Data\TranslationOptions::PHP,

    'phpHandlerClass' => \BrunosCode\TranslationHandler\PhpFileHandler::class,
    'csvHandlerClass' => \BrunosCode\TranslationHandler\CsvFileHandler::class,
    'jsonHandlerClass' => \BrunosCode\TranslationHandler\JsonFileHandler::class,
    'dbHandlerClass' => \BrunosCode\Twill2TranslationHandler\DatabaseHandler::class,

    'phpFormat' => false,
    'phpPath' => lang_path(),

    'csvDelimiter' => ';',
    'csvFileName' => 'translations',
    'csvPath' => storage_path('lang'),

    'jsonPath' => lang_path(),
];
