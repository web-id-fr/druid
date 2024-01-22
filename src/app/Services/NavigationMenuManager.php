<?php

namespace Webid\Druid\App\Services;

use Webid\Druid\App\Dto\Menu;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Repositories\MenuRepository;

class NavigationMenuManager
{
    public function __construct(private readonly MenuRepository $menuRepository)
    {

    }

    public function getBySlugAndLang(string $menuSlug, Langs $lang): Menu
    {
        $menu = $this->menuRepository->findOrFailBySlugAndLang($menuSlug, $lang);

        return Menu::fromMenu($menu);
    }

    public function getBySlug(string $menuSlug): Menu
    {
        return $this->getBySlugAndLang($menuSlug, getCurrentLocale());
    }
}
