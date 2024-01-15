<?php

namespace Webid\Druid\Services;

use Webid\Druid\Dto\Menu;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Repositories\MenuRepository;

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
}
