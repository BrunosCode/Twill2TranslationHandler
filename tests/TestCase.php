<?php

namespace BrunosCode\Twill2TranslationHandler\Tests;

use BrunosCode\Twill2TranslationHandler\Twill2TranslationHandlerServiceProvider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Twill2TranslationHandler\\Twill2TranslationHandler\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            Twill2TranslationHandlerServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_twill-2-translation-handler_table.php.stub';
        $migration->up();
        */
    }
}
