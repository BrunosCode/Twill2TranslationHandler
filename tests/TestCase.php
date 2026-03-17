<?php

namespace BrunosCode\TwillTranslationHandler\Tests;

use A17\Twill\TwillServiceProvider;
use BrunosCode\TranslationHandler\TranslationHandlerServiceProvider;
use BrunosCode\TwillTranslationHandler\TranslationsCapsuleServiceProvider;
use BrunosCode\TwillTranslationHandler\TwillTranslationHandlerServiceProvider;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->app['db']->connection()->getDriverName() === 'sqlite') {
            $this->app['db']->connection()->statement('PRAGMA foreign_keys = ON');
        }
    }

    protected function tearDown(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->app['db']->connection()->getSchemaBuilder()->getTables() as $table) {
            if ($table['name'] !== 'migrations') {
                Schema::drop($table['name']);
            }
        }

        Schema::enableForeignKeyConstraints();

        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            TwillServiceProvider::class,
            TranslationHandlerServiceProvider::class,
            TwillTranslationHandlerServiceProvider::class,
            TranslationsCapsuleServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('translation-handler.locales', ['en', 'it']);
        $app['config']->set('translation-handler.fileNames', ['test']);
        $app['config']->set('translatable.locales', ['en', 'it']);
        $app['config']->set('twill.admin_app_url', '');
        $app['config']->set('twill.admin_app_path', 'admin');
    }

    protected function defineDatabaseMigrations()
    {
        // Twill core tables
        $this->loadMigrationsFrom(
            __DIR__.'/../vendor/area17/twill/migrations/default'
        );

        // Translation tables (complete with Twill columns)
        Schema::create('translation_keys', function ($table) {
            $table->id();
            $table->string('key')->unique();
            $table->boolean('published')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('translation_values', function ($table) {
            $table->id();
            $table->foreignId('translation_key_id')
                ->constrained('translation_keys')
                ->onDelete('cascade');
            $table->string('locale', 7);
            $table->text('value')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['translation_key_id', 'locale']);
        });

        Schema::create('translation_groups', function ($table) {
            $table->id();
            $table->string('prefix')->unique();
            $table->boolean('published')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
