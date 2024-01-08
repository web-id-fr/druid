<?php

namespace Webid\Druid\Dto;

use Illuminate\Support\Collection;
use Webid\Druid\Enums\MenuItemTarget;

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

    public static function fromMenuItem(\Webid\Druid\Models\MenuItem $menuItem): self
    {
        return new self(
            $menuItem->id,
            strval($menuItem->label ?? $menuItem->model?->getMenuLabel()),
            $menuItem->custom_url ?? strval($menuItem->model?->getFullPathUrl()),
            $menuItem->target,
            $menuItem->children->map(fn (\Webid\Druid\Models\MenuItem $item) => MenuItem::fromMenuItem($item))
        );
    }
}
