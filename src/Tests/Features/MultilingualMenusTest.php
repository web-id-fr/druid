<?php

namespace Features;

use Illuminate\Database\UniqueConstraintViolationException;
use Tests\TestCase;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Tests\Helpers\MenuCreator;
use Webid\Druid\Tests\Helpers\MultilingualHelpers;

class MultilingualMenusTest extends TestCase
{
    use MenuCreator;
    use MultilingualHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->disableMultilingualFeature();
    }

    /** @test */
    public function two_menus_can_share_the_same_slug_if_not_in_the_same_lang(): void
    {
        $this->enableMultilingualFeature();

        $menuSlug = 'menu-slug';
        $menuInEnglish = $this->createMenuWithSlug($menuSlug, lang: Langs::EN);
        $menuInFrench = $this->createFrenchTranslationMenu(fromMenu: $menuInEnglish);

        $this->assertEquals($menuInEnglish->slug, $menuSlug);
        $this->assertEquals($menuInFrench->slug, $menuSlug);
    }

    /** @test */
    public function two_menus_cannot_share_the_same_slug_and_lang(): void
    {
        $this->enableMultilingualFeature();

        $menuSlug = 'menu-slug';
        $this->createMenuWithSlug($menuSlug, lang: Langs::EN);

        $this->expectException(UniqueConstraintViolationException::class);
        $this->createMenuWithSlug($menuSlug, lang: Langs::EN);
    }

    /** @test */
    public function a_menu_can_have_translations(): void
    {
        $this->enableMultilingualFeature();

        $menuSlug = 'menu-slug';
        $originMenu = $this->createMenuWithSlug($menuSlug, lang: Langs::EN);

        $this->assertEmpty($originMenu->translations);

        $frenchTranslation = $this->createFrenchTranslationMenu(fromMenu: $originMenu);
        $originMenu->refresh();

        $this->assertCount(1, $originMenu->translations);
        $this->assertTrue($originMenu->translations->first()->is($frenchTranslation));

        $this->assertCount(1, $originMenu->translations);
    }
}
