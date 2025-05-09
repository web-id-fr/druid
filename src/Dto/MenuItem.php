<?php

namespace Webid\Druid\Dto;

use Webid\Druid\Models\MenuItem as MenuItemModel;

class MenuItem
{
    private array $attributes = [];

    private function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->attributes[$name] = $value;
    }

    public static function fromMenuItem(MenuItemModel $menuItem): self
    {
        $attributes = [
            'id' => $menuItem->id,
            'label' => strval($menuItem->label ?? $menuItem->model?->getMenuLabel()),
            'url' => $menuItem->custom_url ?? url($menuItem->model?->fullUrlPath()),
            'target' => $menuItem->target,
            'children' => $menuItem->children->map(fn (MenuItemModel $item) => self::fromMenuItem($item)),
        ];

        foreach ($menuItem->getAttributes() as $key => $value) {
            if (!array_key_exists($key, $attributes)) {
                $attributes[$key] = $value;
            }
        }

        return new self($attributes);
    }
}
