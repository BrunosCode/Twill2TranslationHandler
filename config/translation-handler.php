<?php

use BrunosCode\TranslationHandler\CsvFileHandler;
use BrunosCode\TranslationHandler\Data\TranslationOptions;
use BrunosCode\TranslationHandler\JsonFileHandler;
use BrunosCode\TranslationHandler\PhpFileHandler;
use BrunosCode\TwillTranslationHandler\DatabaseHandler;

// config for BrunosCode/TranslationHandler

return [
    /*
    |--------------------------------------------------------------------------
    | Legacy Twill navigation
    |--------------------------------------------------------------------------
    | Set to true to auto-register the Translations entries via the legacy
    | twill-navigation config array. Leave false if your app uses the
    | TwillNavigation builder API and you register the entries yourself.
    */
    'legacy-twill-navigation' => true,

    'keyDelimiter' => '.',

    'fileNames' => ['test'],
    'locales' => ['en', 'it'],

    'defaultImportFrom' => TranslationOptions::PHP,
    'defaultImportTo' => TranslationOptions::DB,
    'defaultExportFrom' => TranslationOptions::DB,
    'defaultExportTo' => TranslationOptions::PHP,

    'phpHandlerClass' => PhpFileHandler::class,
    'csvHandlerClass' => CsvFileHandler::class,
    'jsonHandlerClass' => JsonFileHandler::class,
    'dbHandlerClass' => DatabaseHandler::class,

    'phpFormat' => false,
    'phpPath' => lang_path(),

    'csvDelimiter' => ';',
    'csvFileName' => 'translations',
    'csvPath' => storage_path('lang'),

    'jsonPath' => lang_path(),
];
