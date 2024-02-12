<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Models\Menu;
use Webid\Druid\App\Models\MenuItem;

trait MenuCreator
{
    protected function createMenu(array $params = []): Menu
    {
        return Menu::factory($params)->create();
    }

    protected function createMenuWithSlug(string $slug, array $params = [], ?Langs $lang = Langs::EN): Menu
    {
        return $this->createMenu(['slug' => $slug, 'lang' => $lang, ...$params]);
    }

    protected function addItemToMenu(Menu $menu, array $params = []): MenuItem
    {
        return MenuItem::factory($params)->forMenu($menu)->create();
    }

    protected function addPageItemToMenu(Menu $menu, array $params = []): MenuItem
    {
        return MenuItem::factory($params)->forMenu($menu)->withPageItem()->create();
    }

    protected function createFrenchTranslationMenu(array $params = [], ?Menu $fromMenu = null): Menu
    {
        if ($fromMenu) {
            $params['translation_origin_model_id'] = $fromMenu->getKey();
            $params['slug'] = $fromMenu->slug;
        }

        return Menu::factory([...$params, 'lang' => Langs::FR->value])->create();
    }
}
