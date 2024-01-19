<?php

namespace Features;

use Tests\TestCase;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Tests\Helpers\ApiHelpers;
use Webid\Druid\Tests\Helpers\MultilingualHelpers;
use Webid\Druid\Tests\Helpers\PageCreator;

class MultilingualBaseTest extends TestCase
{
    use ApiHelpers;
    use MultilingualHelpers;
    use PageCreator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->disableMultilingualFeature();
    }

    /** @test */
    public function multilingual_feature_can_be_enabled_and_disabled_using_config(): void
    {
        $this->assertFalse(isMultilingualEnabled());
        $this->enableMultilingualFeature();
        $this->assertTrue(isMultilingualEnabled());
    }

    /** @test */
    public function default_locale_can_be_set_using_config(): void
    {
        $this->setDefaultLanguageKey('fr');
        $this->assertEquals(getDefaultLocaleKey(), 'fr');
        $this->setDefaultLanguageKey('de');
        $this->assertEquals(getDefaultLocale(), Langs::DE);
    }

    /** @test */
    public function current_locale_can_be_found_anytime_with_a_fallback_value(): void
    {
        $this->setDefaultLanguageKey('fr');
        $this->assertEquals(getCurrentLocale(), Langs::FR);
    }
}
