<?php

declare(strict_types=1);

use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Dto\Menu;
use Webid\Druid\App\Services\NavigationMenuManager;

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
