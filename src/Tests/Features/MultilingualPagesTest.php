<?php

namespace Webid\Druid\Tests\Features;

use App\Enums\Langs;
use Illuminate\Database\UniqueConstraintViolationException;
use Tests\TestCase;
use Webid\Druid\Tests\Helpers\ApiHelpers;
use Webid\Druid\Tests\Helpers\MultilingualHelpers;
use Webid\Druid\Tests\Helpers\PageCreator;

class MultilingualPagesTest extends TestCase
{
    use PageCreator;
    use MultilingualHelpers;
    use ApiHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->disableMultilingualFeature();
    }

    /** @test */
    public function current_language_shows_up_in_url_when_multilingual_feature_is_enabled(): void
    {
        $page = $this->createPageInEnglish();

        $this->assertFalse(config('cms.enable_multilingual_feature'));
        $this->assertEquals($page->url(), url($page->slug));

        $this->enableMultilingualFeature();

        $this->assertEquals($page->url(), url('/en/' . $page->slug));
    }

    /** @test */
    public function page_can_be_accessible_in_other_language_than_the_default_one(): void
    {
        $this->enableMultilingualFeature();
        $page = $this->createFrenchTranslationPage();

        $this->assertEquals($page->url(), url('/fr/' . $page->slug));

        $this->get($page->url())
            ->assertOk();
    }

    /** @test */
    public function page_url_without_lang_leads_to_a_404(): void
    {
        $page = $this->createPageInEnglish();
        $pageUrlWithoutLang = $page->url();

        $this->get($pageUrlWithoutLang)
            ->assertOk();

        $this->enableMultilingualFeature();

        $this->get($pageUrlWithoutLang)
            ->assertStatus(404);
    }

    /** @test */
    public function two_pages_can_share_the_same_slug_if_not_in_the_same_lang(): void
    {
        $this->enableApiMode();
        $this->enableMultilingualFeature();

        $pageSlug = 'page-slug';
        $pageInEnglish = $this->createPageInEnglish(['slug' => $pageSlug]);
        $pageInFrench = $this->createFrenchTranslationPage(['slug' => $pageSlug]);

        $this->assertEquals($pageInEnglish->slug, $pageSlug);
        $this->assertEquals($pageInFrench->slug, $pageSlug);

        $this->get($pageInEnglish->url())->assertJsonFragment(['id' => $pageInEnglish->getKey()]);
        $this->get($pageInEnglish->url())->assertJsonFragment(['lang' => Langs::EN->value]);
        $this->get($pageInFrench->url())->assertJsonFragment(['id' => $pageInFrench->getKey()]);
        $this->get($pageInFrench->url())->assertJsonFragment(['lang' => Langs::FR->value]);
    }

    /** @test */
    public function two_pages_cannot_share_the_same_slug_and_lang(): void
    {
        $this->enableMultilingualFeature();

        $pageSlug = 'page-slug';
        $this->createPageInEnglish(['slug' => $pageSlug]);

        $this->expectException(UniqueConstraintViolationException::class);
        $this->createPageInEnglish(['slug' => $pageSlug]);
    }

    /** @test */
    public function a_page_can_have_translations(): void
    {
        $this->enableMultilingualFeature();

        $originPage = $this->createPageInEnglish();

        $this->assertEmpty($originPage->translations);

        $frenchTranslation = $this->createFrenchTranslationPage(fromPage: $originPage);
        $originPage->refresh();

        $this->assertCount(1, $originPage->translations);
        $this->assertTrue($originPage->translations->first()->is($frenchTranslation));

        $this->createGermanTranslationPage(fromPage: $originPage);
        $originPage->refresh();

        $this->assertCount(2, $originPage->translations);
    }
}
