<?php

namespace BrunosCode\Twill2TranslationHandler;

use A17\Twill\Facades\TwillCapsules;
use A17\Twill\TwillPackageServiceProvider;
use Illuminate\Support\Facades\Config;

class TranslationsCapsuleServiceProvider extends TwillPackageServiceProvider
{
    protected $autoRegisterCapsules = false;

    public function boot(): void
    {
        $this->registerCapsuleWithoutNavigation('Translations');
        $this->registerNavigation();
    }

    protected function registerCapsuleWithoutNavigation(string $name): void
    {
        $namespace = $this->getCapsuleNamespace() . '\\Twill\\Capsules\\' . $name;
        $dir = $this->getPackageDirectory() . DIRECTORY_SEPARATOR
            . 'src' . DIRECTORY_SEPARATOR
            . 'Twill' . DIRECTORY_SEPARATOR
            . 'Capsules' . DIRECTORY_SEPARATOR . $name;

        TwillCapsules::registerPackageCapsule(
            $name,
            $namespace,
            $dir,
            null,
            true,
            false
        );
    }

    protected function registerNavigation(): void
    {
        $config = Config::get('twill-navigation', []);

        $config['translations'] = [
            'title' => 'Translations',
            'route' => 'admin.translations.index',
            'primary_navigation' => [
                'translations' => [
                    'title' => 'Keys',
                    'route' => 'admin.translations.index',
                ],
                'translationTools' => [
                    'title' => 'Import / Export',
                    'route' => 'admin.translationTools.index',
                ],
            ],
        ];

        Config::set('twill-navigation', $config);
    }
}
