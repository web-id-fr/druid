<?php

namespace Webid\Druid\Dto;

use Illuminate\Support\Collection;

class Menu
{
    private function __construct(
        readonly public string $title,
        readonly public string $slug,
        readonly public Collection $items,
    ) {
    }

    public static function fromMenu(\Webid\Druid\Models\Menu $menu): self
    {
        return new self(
            $menu->title,
            $menu->slug,
            $menu->level0Items->map(fn (\Webid\Druid\Models\MenuItem $item) => MenuItem::fromMenuItem($item))
        );
    }
}
