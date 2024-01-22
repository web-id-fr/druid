<?php

namespace Webid\Druid\App\Dto;

use Webid\Druid\App\Models\MenuItem as MenuItemModel;
use Illuminate\Support\Collection;
use Webid\Druid\App\Enums\MenuItemTarget;

class MenuItem
{
    private function __construct(
        readonly public int $id,
        readonly public string $label,
        readonly public string $url,
        readonly public MenuItemTarget $target,
        readonly public Collection $children,
    ) {
    }

    public static function fromMenuItem(MenuItemModel $menuItem): self
    {
        return new self(
            $menuItem->id,
            strval($menuItem->label ?? $menuItem->model?->getMenuLabel()),
            $menuItem->custom_url ?? strval($menuItem->model?->fullUrlPath()),
            $menuItem->target,
            $menuItem->children->map(fn (MenuItemModel $item) => MenuItem::fromMenuItem($item))
        );
    }
}
