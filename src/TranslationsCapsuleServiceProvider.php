<?php

namespace BrunosCode\TwillTranslationHandler;

use A17\Twill\Facades\TwillCapsules;
use A17\Twill\TwillPackageServiceProvider;
use Illuminate\Support\Facades\Config;

class TranslationsCapsuleServiceProvider extends TwillPackageServiceProvider
{
    protected $autoRegisterCapsules = false;

    public function boot(): void
    {
        $capsulePath = $this->getPackageDirectory().DIRECTORY_SEPARATOR
            .'src'.DIRECTORY_SEPARATOR
            .'Twill'.DIRECTORY_SEPARATOR
            .'Capsules'.DIRECTORY_SEPARATOR.'Translations';

        $baseNamespace = $this->getCapsuleNamespace().'\\Twill\\Capsules\\Translations';

        // Register Translations module
        TwillCapsules::registerPackageCapsule(
            'Translations',
            $baseNamespace,
            $capsulePath,
            null,
            true,
            false
        );

        // Register TranslationGroups module (same capsule path, same namespace)
        TwillCapsules::registerPackageCapsule(
            'TranslationGroups',
            $baseNamespace,
            $capsulePath,
            null,
            true,
            false
        );

        $this->registerNavigation();
    }

    protected function registerNavigation(): void
    {
        $prefix = config('twill.admin_route_name_prefix', 'admin.');
        $config = Config::get('twill-navigation', []);

        $config['translations'] = [
            'title' => 'Translations',
            'route' => $prefix.'translations.translations.index',
            'primary_navigation' => [
                'translations' => [
                    'title' => 'Translations',
                    'route' => $prefix.'translations.translations.index',
                ],
                'translationGroups' => [
                    'title' => 'Groups',
                    'route' => $prefix.'translations.translationGroups.index',
                ],
                'translationTools' => [
                    'title' => 'Import / Export',
                    'route' => $prefix.'translations.translationTools.index',
                ],
            ],
        ];

        Config::set('twill-navigation', $config);
    }
}
