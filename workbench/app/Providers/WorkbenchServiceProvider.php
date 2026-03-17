<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Env;
use Illuminate\Support\ServiceProvider;

class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Env::getRepository()->set('ADMIN_APP_URL', '');
        Env::getRepository()->set('ADMIN_APP_PATH', 'admin');

        config()->set('twill.admin_app_url', '');
        config()->set('twill.admin_app_path', 'admin');
        config()->set('translatable.locales', ['en', 'it']);
    }

    public function boot(): void
    {
        //
    }
}
