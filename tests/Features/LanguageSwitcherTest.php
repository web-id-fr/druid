<?php

namespace Webid\Druid\Tests\Features;

use Webid\Druid\App\Enums\Langs;
use Webid\Druid\Tests\Helpers\ApiHelpers;
use Webid\Druid\Tests\Helpers\MultilingualHelpers;
use Webid\Druid\Tests\Helpers\PageCreator;
use Webid\Druid\Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    use ApiHelpers;
    use MultilingualHelpers;
    use PageCreator;

    public function setUp(): void
    {
        parent::setUp();

        $this->enableMultilingualFeature();
        $this->setLocalesList();
    }

    /** @test */
    public function language_switcher_shows_the_list_of_locales_in_the_same_order_as_config_file(): void
    {
        $links = $this->getLanguageSwitcher()->getLinks();

        $this->assertCount(3, $links);
        $this->assertEquals($links->first(), Langs::EN);
        $this->assertEquals($links->get(1), Langs::FR);
        $this->assertEquals($links->get(2), Langs::DE);
    }
}
