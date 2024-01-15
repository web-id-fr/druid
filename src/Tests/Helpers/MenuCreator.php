<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\Database\Factories\MenuFactory;
use Webid\Druid\Database\Factories\MenuItemFactory;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Models\Menu;
use Webid\Druid\Models\MenuItem;

trait MenuCreator
{
    protected function createMenu(array $params = []): Menu
    {
        /** @var Menu $menu */
        $menu = MenuFactory::new()->create($params);

        return $menu;
    }

    protected function createMenuWithSlug(string $slug, array $params = [], ?Langs $lang = Langs::EN): Menu
    {
        return $this->createMenu(['slug' => $slug, 'lang' => $lang, ...$params]);
    }

    protected function addItemToMenu(Menu $menu, array $params = []): MenuItem
    {
        return MenuItemFactory::new()->forMenu($menu)->create($params);
    }

    protected function addPageItemToMenu(Menu $menu, array $params = []): MenuItem
    {
        return MenuItemFactory::new()->forMenu($menu)->withPageItem()->create($params);
    }

    protected function createFrenchTranslationMenu(array $params = [], ?Menu $fromMenu = null): Menu
    {
        if ($fromMenu) {
            $params['translation_origin_model_id'] = $fromMenu->getKey();
            $params['slug'] = $fromMenu->slug;
        }

        /** @var Menu $menu */
        $menu = MenuFactory::new()->create([...$params, 'lang' => Langs::FR->value]);

        return $menu;
    }
}
