<?php

namespace Webid\Druid\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Webid\Druid\Models\MenuItem;

class MenuItemRepository
{
    public function __construct(private readonly MenuItem $model)
    {
    }

    public function all(): Collection
    {
        return $this->model->newQuery()
            ->get();
    }

    /**
     * @return array<int, string>
     */
    public function allPluckedByIdAndLabel(?int $parentMenuId = null): array
    {
        /** @var array<int, string> $menus */
        $menus = $this->all()
            ->when($parentMenuId, fn ($query) => $query->where('menu_id', $parentMenuId))
            ->pluck('label', 'id')
            ->map(function ($label, $id) {
                return $label ?? 'Item ID #'.$id;
            })
            ->toArray();

        return $menus;
    }
}
