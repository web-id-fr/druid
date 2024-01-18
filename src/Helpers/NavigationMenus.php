<?php

declare(strict_types=1);

use Webid\Druid\Dto\Menu;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Services\NavigationMenuManager;

if (! function_exists('getNavigationMenuBySlug')) {
    function getNavigationMenuBySlug(string $slug): Menu
    {
        /** @var NavigationMenuManager $navigationMenuManager */
        $navigationMenuManager = app()->make(NavigationMenuManager::class);

        return $navigationMenuManager->getBySlug($slug);
    }
}

if (! function_exists('getNavigationMenuBySlugAndLang')) {
    function getNavigationMenuBySlugAndLang(string $slug, Langs $lang): Menu
    {
        /** @var NavigationMenuManager $navigationMenuManager */
        $navigationMenuManager = app()->make(NavigationMenuManager::class);

        return $navigationMenuManager->getBySlugAndLang($slug, $lang);
    }
}
