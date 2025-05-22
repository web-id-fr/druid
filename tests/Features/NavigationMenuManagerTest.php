<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webid\Druid\Dto\Menu;
use Webid\Druid\Services\NavigationMenuManager;

uses(\Webid\Druid\Tests\Helpers\MenuCreator::class);
uses(\Webid\Druid\Tests\Helpers\MultilingualHelpers::class);

beforeEach(function () {
    /** @var NavigationMenuManager $navigationMenuManager */
    $navigationMenuManager = app(NavigationMenuManager::class);
    $this->navigationMenuManager = $navigationMenuManager;
});

test('we can get a menu with its slug', function () {
    $this->disableMultilingualFeature();
    $this->createMenuWithSlug('my-menu');

    $menuResource = $this->navigationMenuManager->getBySlug('my-menu');

    expect($menuResource)->not->toBeEmpty()
        ->and($menuResource)->toBeInstanceOf(Menu::class);
});

test('a wrong menu slug throws an exception', function () {
    try {
        $this->navigationMenuManager->getBySlugAndLang('inexisting-slug', 'fr');
    } catch (ModelNotFoundException $e) {
        expect(true)->toBeTrue();

        return;
    }

    $this->fail('No Model not found exception raised');
});

test('a menu dto is returned when menu slug exists', function () {
    $this->createMenuWithSlug('footer');
    $menuResource = $this->navigationMenuManager->getBySlugAndLang('footer', 'en');

    expect($menuResource)->not->toBeEmpty()
        ->and($menuResource)->toBeInstanceOf(Menu::class);
});

test('menu items label can be set manually', function () {
    $menu = $this->createMenuWithSlug('footer');
    $this->addPageItemToMenu($menu, ['label' => 'Custom Label']);

    $menuResource = $this->navigationMenuManager->getBySlugAndLang('footer', 'en');

    expect('Custom Label')->toEqual($menuResource->items->first()->label);
});

test('the model title overrides label if empty', function () {
    $menu = $this->createMenuWithSlug('footer');
    $menuItem = $this->addPageItemToMenu($menu, ['label' => null]);
    $this->addPageItemToMenu($menu, ['parent_item_id' => $menuItem->getKey()]);

    $menuResource = $this->navigationMenuManager->getBySlugAndLang('footer', 'en');
    expect($menu->level0Items->first()->model->title)->toEqual($menuResource->items->first()->label);
});

it('ems are sorted by order param asc', function () {
    $menu = $this->createMenuWithSlug('footer');
    $menuItemOrder20 = $this->addPageItemToMenu($menu, ['order' => 20]);
    $menuItemNoOrder = $this->addPageItemToMenu($menu, ['order' => null]);
    $menuItemOrder5 = $this->addPageItemToMenu($menu, ['order' => 5]);

    $subMenuItemOrder5 = $this->addPageItemToMenu($menu, ['parent_item_id' => $menuItemOrder5->getKey(), 'order' => 5]);
    $subMenuItemNoOrder = $this->addPageItemToMenu($menu, ['parent_item_id' => $menuItemOrder5->getKey(), 'order' => null]);
    $subMenuItemOrder20 = $this->addPageItemToMenu($menu, ['parent_item_id' => $menuItemOrder5->getKey(), 'order' => 20]);

    $subMenuItemLevel2Order5 = $this->addPageItemToMenu($menu, ['parent_item_id' => $subMenuItemOrder5->getKey(), 'order' => 5]);
    $subMenuItemLevel2NoOrder = $this->addPageItemToMenu($menu, ['parent_item_id' => $subMenuItemOrder5->getKey(), 'order' => null]);
    $subMenuItemLevel2Order20 = $this->addPageItemToMenu($menu, ['parent_item_id' => $subMenuItemOrder5->getKey(), 'order' => 20]);

    $menuResource = $this->navigationMenuManager->getBySlugAndLang('footer', 'en');
    expect($menuItemNoOrder->getKey())->toEqual($menuResource->items->first()->id)
        ->and($menuItemOrder5->getKey())->toEqual($menuResource->items->get(1)->id)
        ->and($menuItemOrder20->getKey())->toEqual($menuResource->items->get(2)->id)
        ->and($subMenuItemNoOrder->getKey())->toEqual($menuResource->items->get(1)->children->first()->id)
        ->and($subMenuItemOrder5->getKey())->toEqual($menuResource->items->get(1)->children->get(1)->id)
        ->and($subMenuItemOrder20->getKey())->toEqual($menuResource->items->get(1)->children->get(2)->id)
        ->and($subMenuItemLevel2NoOrder->getKey())->toEqual($menuResource->items->get(1)->children->get(1)->children->first()->id)
        ->and($subMenuItemLevel2Order5->getKey())->toEqual($menuResource->items->get(1)->children->get(1)->children->get(1)->id)
        ->and($subMenuItemLevel2Order20->getKey())->toEqual($menuResource->items->get(1)->children->get(1)->children->get(2)->id);

});
