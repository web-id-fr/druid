<?php

namespace Webid\Druid\Services;

use Webid\Druid\Dto\Menu;
use Webid\Druid\Repositories\MenuRepository;

class NavigationMenuManager
{
    public function __construct(private readonly MenuRepository $menuRepository)
    {

    }

    public function getBySlug(string $menuSlug): Menu
    {
        $menu = $this->menuRepository->findOrFailBySlug($menuSlug);

        return Menu::fromMenu($menu);
    }
}
