<?php

namespace Webid\Druid\Dto;

use Illuminate\Support\Collection;
use Webid\Druid\Models\Menu as MenuModel;
use Webid\Druid\Models\MenuItem as MenuItemModel;

class Menu
{
    private function __construct(
        readonly public string $title,
        readonly public string $slug,
        readonly public Collection $items,
    ) {}

    public static function fromMenu(MenuModel $menu): self
    {
        return new self(
            $menu->title,
            $menu->slug,
            $menu->level0Items
                ->filter(function ($item) {
                    return $item->custom_url !== null || ($item->model !== null && ! optional($item->model)->trashed());
                })
                ->map(fn (MenuItemModel $item) => MenuItem::fromMenuItem($item))
        );
    }
}
