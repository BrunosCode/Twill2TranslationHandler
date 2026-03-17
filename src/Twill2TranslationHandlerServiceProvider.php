<?php

namespace BrunosCode\Twill2TranslationHandler;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class Twill2TranslationHandlerServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('twill-2-translation-handler')
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

        $router->group([
            'namespace' => 'BrunosCode\Twill2TranslationHandler\Http\Controllers',
            'domain' => config('twill.admin_app_url'),
            'middleware' => [
                config('twill.admin_middleware_group', 'web'),
                'twill_auth:twill_users',
            ],
            'prefix' => config('twill.admin_app_path', ''),
            'as' => 'admin.',
        ], function ($router) {
            $router->get('translationTools', 'TranslationToolsController@index')
                ->name('translationTools.index');
            $router->post('translationTools/export-php', 'TranslationToolsController@exportToPhp')
                ->name('translationTools.exportToPhp');
            $router->post('translationTools/import-php', 'TranslationToolsController@importFromPhp')
                ->name('translationTools.importFromPhp');
            $router->post('translationTools/export-csv', 'TranslationToolsController@exportToCsv')
                ->name('translationTools.exportToCsv');
            $router->post('translationTools/import-csv', 'TranslationToolsController@importFromCsv')
                ->name('translationTools.importFromCsv');
        });
    }
}
