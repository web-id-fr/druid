<?php

namespace Webid\Druid\App\Dto;

use Illuminate\Support\Collection;
use Webid\Druid\App\Models\Menu as MenuModel;
use Webid\Druid\App\Models\MenuItem as MenuItemModel;

class Menu
{
    private function __construct(
        readonly public string $title,
        readonly public string $slug,
        readonly public Collection $items,
    ) {
    }

    public static function fromMenu(MenuModel $menu): self
    {
        return new self(
            $menu->title,
            $menu->slug,
            $menu->level0Items->map(fn (MenuItemModel $item) => MenuItem::fromMenuItem($item))
        );
    }
}
