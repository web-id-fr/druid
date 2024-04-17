<?php

namespace Webid\Druid\Tests\Features;

use Webid\Druid\App\Enums\Langs;
use Webid\Druid\Tests\Helpers\ApiHelpers;
use Webid\Druid\Tests\Helpers\MultilingualHelpers;
use Webid\Druid\Tests\Helpers\PageCreator;
use Webid\Druid\Tests\Helpers\PostCreator;
use Webid\Druid\Tests\TestCase;

class LanguageSwitcherTest extends TestCase
{
    use ApiHelpers;
    use MultilingualHelpers;
    use PageCreator;
    use PostCreator;

    private const SWITCHER_ROUTE_NAME = 'switch_lang';

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

    public function test_user_can_update_locale_with_switch_to_the_same_page_in_other_lang(): void
    {
        $pageSlug = 'page-slug';
        $pageInEnglish = $this->createPageInEnglish(['slug' => $pageSlug]);
        $pageInFrench = $this->createFrenchTranslationPage(['slug' => $pageSlug]);

        $this->assertEquals($pageInEnglish->slug, $pageSlug);
        $this->assertEquals($pageInFrench->slug, $pageSlug);

        $this->from($pageInEnglish->url())
            ->get(route(self::SWITCHER_ROUTE_NAME, [
                'locale' => Langs::FR->value,
            ]))
            ->assertRedirect($pageInFrench->url());
    }

    public function test_user_is_redirect_to_homepage_if_the_same_page_does_not_exist_in_selected_lang(): void
    {
        $pageSlug = 'page-slug';
        $lang = Langs::FR->value;
        $pageInEnglish = $this->createPageInEnglish(['slug' => $pageSlug]);
        $pageInGerman = $this->createGermanTranslationPage(['slug' => $pageSlug]);

        $this->from($pageInEnglish->url())
            ->get(route(self::SWITCHER_ROUTE_NAME, [
                'locale' => $lang,
            ]))
            ->assertRedirect("{$lang}/");
    }

    public function test_user_can_update_locale_with_switch_to_the_same_post_in_other_lang(): void
    {
        $postSlug = 'post-slug';
        $postInEnglish = $this->createPostInEnglish(['slug' => $postSlug]);
        $postInFrench = $this->createFrenchTranslationPost(['slug' => $postSlug]);

        $this->assertEquals($postInEnglish->slug, $postSlug);
        $this->assertEquals($postInFrench->slug, $postSlug);

        $this->from($postInEnglish->url())
            ->get(route(self::SWITCHER_ROUTE_NAME, [
                'locale' => Langs::FR->value,
            ]))
            ->assertRedirect($postInFrench->url());
    }

    public function test_user_is_redirect_to_homepage_if_the_same_post_does_not_exist_in_selected_lang(): void
    {
        $lang = Langs::FR->value;
        $postSlug = 'post-slug';
        $postInEnglish = $this->createPostInEnglish(['slug' => $postSlug]);
        $postInGerman = $this->createGermanTranslationPost(['slug' => $postSlug]);

        $this->from($postInEnglish->url())
            ->get(route(self::SWITCHER_ROUTE_NAME, [
                'locale' => $lang,
            ]))
            ->assertRedirect("{$lang}/");
    }
}
