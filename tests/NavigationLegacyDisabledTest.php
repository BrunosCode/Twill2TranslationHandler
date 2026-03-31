<?php

namespace BrunosCode\TwillTranslationHandler\Tests;

class NavigationLegacyDisabledTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('translation-handler.legacy-twill-navigation', false);
    }

    public function test_does_not_register_twill_navigation_when_legacy_flag_is_false(): void
    {
        $this->assertArrayNotHasKey('translations', config('twill-navigation') ?? []);
    }

    public function test_routes_are_still_registered_when_legacy_flag_is_false(): void
    {
        $this->assertStringContainsString('/translations/translations', route('admin.translations.translations.index'));
        $this->assertStringContainsString('/translations/translationGroups', route('admin.translations.translationGroups.index'));
        $this->assertStringContainsString('/translations/tools', route('admin.translations.translationTools.index'));
    }
}
