<?php

namespace BrunosCode\TwillTranslationHandler;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TwillTranslationHandlerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('twill-translation-handler')
            ->hasConfigFile('translation-handler')
            ->hasViews()
            ->hasInstallCommand(function (InstallCommand $command) {
                $command->publishConfigFile();
            });
    }

    public function packageBooted(): void
    {
        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes(): void
    {
        $router = app('router');

        $groupConfig = [
            'namespace' => 'BrunosCode\TwillTranslationHandler\Http\Controllers',
            'middleware' => [
                config('twill.admin_middleware_group', 'web'),
                'twill_auth:twill_users',
            ],
            'prefix' => config('twill.admin_app_path', ''),
            'as' => 'admin.',
        ];

        if ($domain = config('twill.admin_app_url')) {
            $groupConfig['domain'] = $domain;
        }

        $router->group($groupConfig, function ($router) {
            $router->get('translations/tools', 'TranslationToolsController@index')
                ->name('translations.translationTools.index');
            $router->post('translations/tools/export-csv', 'TranslationToolsController@exportToCsv')
                ->name('translations.translationTools.exportToCsv');
            $router->post('translations/tools/import-csv', 'TranslationToolsController@importFromCsv')
                ->name('translations.translationTools.importFromCsv');
            $router->get('translations/translationGroups/{id}/export-csv', 'TranslationToolsController@exportGroupCsv')
                ->name('translations.translationGroups.exportCsv');
            $router->post('translations/translationGroups/{id}/import-csv', 'TranslationToolsController@importGroupCsv')
                ->name('translations.translationGroups.importCsv');
        });
    }
}
