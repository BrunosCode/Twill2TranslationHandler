<?php

namespace BrunosCode\TwillTranslationHandler\Tests;

class NavigationLegacyEnabledTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);
        $app['config']->set('translation-handler.legacy-twill-navigation', true);
    }

    public function test_registers_twill_navigation_when_legacy_flag_is_true(): void
    {
        $nav = config('twill-navigation');

        $this->assertArrayHasKey('translations', $nav);
        $this->assertArrayHasKey('primary_navigation', $nav['translations']);
        $this->assertArrayHasKey('translations', $nav['translations']['primary_navigation']);
        $this->assertArrayHasKey('translationGroups', $nav['translations']['primary_navigation']);
        $this->assertArrayHasKey('translationTools', $nav['translations']['primary_navigation']);
    }

    public function test_navigation_routes_point_to_correct_names_when_legacy_flag_is_true(): void
    {
        $nav = config('twill-navigation.translations.primary_navigation');

        $this->assertSame('admin.translations.translations.index', $nav['translations']['route']);
        $this->assertSame('admin.translations.translationGroups.index', $nav['translationGroups']['route']);
        $this->assertSame('admin.translations.translationTools.index', $nav['translationTools']['route']);
    }
}
