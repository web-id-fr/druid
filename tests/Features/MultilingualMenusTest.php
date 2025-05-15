<?php

use Illuminate\Database\UniqueConstraintViolationException;
use Webid\Druid\Facades\Druid;

uses(\Webid\Druid\Tests\Helpers\MenuCreator::class);

uses(\Webid\Druid\Tests\Helpers\MultilingualHelpers::class);

beforeEach(function () {
    $this->disableMultilingualFeature();
});

test('two menus can share the same slug if not in the same lang', function () {
    $this->enableMultilingualFeature();

    $menuSlug = 'menu-slug';
    $menuInEnglish = $this->createMenuWithSlug($menuSlug, lang: 'en');
    $menuInFrench = $this->createFrenchTranslationMenu(fromMenu: $menuInEnglish);

    expect($menuSlug)->toEqual($menuInEnglish->slug)
        ->and($menuSlug)->toEqual($menuInFrench->slug);
});

test('two menus cannot share the same slug and lang', function () {
    $this->enableMultilingualFeature();

    $menuSlug = 'menu-slug';
    $this->createMenuWithSlug($menuSlug, lang: 'en');

    $this->expectException(UniqueConstraintViolationException::class);
    $this->createMenuWithSlug($menuSlug, lang: 'en');
});

test('a menu can have translations', function () {
    $this->enableMultilingualFeature();

    $menuSlug = 'menu-slug';
    $originMenu = $this->createMenuWithSlug($menuSlug, lang: 'en');

    expect($originMenu->translations)->toBeEmpty();

    $frenchTranslation = $this->createFrenchTranslationMenu(fromMenu: $originMenu);
    $originMenu->refresh();

    expect($originMenu->translations)->toHaveCount(1)
        ->and($originMenu->translations->first()->is($frenchTranslation))->toBeTrue()
        ->and($originMenu->translations)->toHaveCount(1);

});

test('a menu is automatically loaded with the current language', function () {
    $this->enableMultilingualFeature();

    $menuSlug = 'menu-slug';
    $originMenu = $this->createMenuWithSlug($menuSlug, lang: 'en');

    expect($originMenu->translations)->toBeEmpty();

    $frenchTranslation = $this->createFrenchTranslationMenu(fromMenu: $originMenu);
    $originMenu->refresh();

    expect($originMenu->translations)->toHaveCount(1)
        ->and($originMenu->translations->first()->is($frenchTranslation))->toBeTrue()
        ->and($originMenu->translations)->toHaveCount(1);

});

test('the are helpers to get menus', function () {
    $this->enableMultilingualFeature();

    $menuSlug = 'menu-slug';
    $originMenu = $this->createMenuWithSlug($menuSlug, lang: 'en');
    $frenchTranslation = $this->createFrenchTranslationMenu(fromMenu: $originMenu);

    $menu = Druid::getNavigationMenuBySlug($menuSlug);
    expect($menuSlug)->toEqual($menu->slug)
        ->and($originMenu->title)->toEqual($menu->title)
        ->and($originMenu->items->count())->toEqual($menu->items->count());

    $menu = Druid::getNavigationMenuBySlugAndLang($menuSlug, 'fr');
    expect($menuSlug)->toEqual($menu->slug)
        ->and($frenchTranslation->title)->toEqual($menu->title)
        ->and($frenchTranslation->items->count())->toEqual($menu->items->count());
});
