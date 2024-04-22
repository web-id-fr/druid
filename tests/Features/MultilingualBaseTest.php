<?php

namespace Webid\Druid\Tests\Features;

use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Facades\Druid;
use Webid\Druid\Tests\Helpers\ApiHelpers;
use Webid\Druid\Tests\Helpers\MultilingualHelpers;
use Webid\Druid\Tests\Helpers\PageCreator;
use Webid\Druid\Tests\TestCase;

class MultilingualBaseTest extends TestCase
{
    use ApiHelpers;
    use MultilingualHelpers;
    use PageCreator;

    public function setUp(): void
    {
        parent::setUp();

        $this->disableMultilingualFeature();
    }

    /** @test */
    public function multilingual_feature_can_be_enabled_and_disabled_using_config(): void
    {
        $this->assertFalse(Druid::isMultilingualEnabled());
        $this->enableMultilingualFeature();
        $this->assertTrue(Druid::isMultilingualEnabled());
    }

    /** @test */
    public function default_locale_can_be_set_using_config(): void
    {
        $this->setDefaultLanguageKey('fr');
        $this->assertEquals(Druid::getDefaultLocaleKey(), 'fr');
        $this->setDefaultLanguageKey('de');
        $this->assertEquals(Druid::getDefaultLocale(), Langs::DE);
    }

    /** @test */
    public function current_locale_can_be_found_anytime_with_a_fallback_value(): void
    {
        $this->setDefaultLanguageKey('fr');
        $this->assertEquals(Druid::getCurrentLocale(), Langs::FR);
    }
}
