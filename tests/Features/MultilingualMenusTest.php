<?php

namespace Webid\Druid\Tests\Features;

use Illuminate\Database\UniqueConstraintViolationException;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Facades\Druid;
use Webid\Druid\Tests\Helpers\MenuCreator;
use Webid\Druid\Tests\Helpers\MultilingualHelpers;
use Webid\Druid\Tests\TestCase;

class MultilingualMenusTest extends TestCase
{
    use MenuCreator;
    use MultilingualHelpers;

    public function setUp(): void
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

    /** @test */
    public function a_menu_is_automatically_loaded_with_the_current_language(): void
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

    /** @test */
    public function the_are_helpers_to_get_menus(): void
    {
        $this->enableMultilingualFeature();

        $menuSlug = 'menu-slug';
        $originMenu = $this->createMenuWithSlug($menuSlug, lang: Langs::EN);
        $frenchTranslation = $this->createFrenchTranslationMenu(fromMenu: $originMenu);

        $menu = Druid::getNavigationMenuBySlug($menuSlug);
        $this->assertEquals($menu->slug, $menuSlug);
        $this->assertEquals($menu->title, $originMenu->title);
        $this->assertEquals($menu->items->count(), $originMenu->items->count());

        $menu = Druid::getNavigationMenuBySlugAndLang($menuSlug, Langs::FR);
        $this->assertEquals($menu->slug, $menuSlug);
        $this->assertEquals($menu->title, $frenchTranslation->title);
        $this->assertEquals($menu->items->count(), $frenchTranslation->items->count());
    }
}
