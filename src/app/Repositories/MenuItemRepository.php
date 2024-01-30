<?php

namespace Webid\Druid\App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Webid\Druid\App\Models\MenuItem;

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
    public function allPluckedByIdAndLabel(): array
    {
        /** @var array<int, string> $menus */
        $menus = $this->all()
            ->pluck('label', 'id')
            ->map(function ($label, $id) {
                return $label ?? 'Item ID #'.$id;
            })
            ->toArray();

        return $menus;
    }
}
