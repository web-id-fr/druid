<?php

namespace Webid\Druid\App\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webid\Druid\App\Dto\Menu;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Facades\Druid;
use Webid\Druid\App\Repositories\MenuRepository;

class NavigationMenuManager
{
    public function __construct(private readonly MenuRepository $menuRepository)
    {

    }

    /**
     * @throws ModelNotFoundException
     */
    public function getById(int $menuId): Menu
    {
        $menu = $this->menuRepository->findOrFailById($menuId);

        return Menu::fromMenu($menu);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function getBySlugAndLang(string $menuSlug, Langs $lang): Menu
    {
        $menu = $this->menuRepository->findOrFailBySlugAndLang($menuSlug, $lang);

        return Menu::fromMenu($menu);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function getBySlug(string $menuSlug): Menu
    {
        return $this->getBySlugAndLang($menuSlug, Druid::getCurrentLocale());
    }
}
