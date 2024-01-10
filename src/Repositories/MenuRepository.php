<?php

namespace Webid\Druid\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webid\Druid\Models\Menu;

class MenuRepository
{
    public function __construct(private readonly Menu $model)
    {
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findOrFailBySlug(string $slug): Menu
    {
        /** @var Menu $model */
        $model = $this->model->newQuery()
            ->with([
                'level0Items' => function (HasMany $query) {
                    $query->orderBy('order');
                },
                'level0Items.children' => function (HasMany $query) {
                    $query->orderBy('order');
                },
                'level0Items.children.children' => function (HasMany $query) {
                    $query->orderBy('order');
                },
                'level0Items.model.parent',
                'level0Items.children.model.parent',
                'level0Items.children.children.model.parent',
                'level0Items.children.children.children.model.parent',
            ])
            ->where('slug', $slug)
            ->firstOrFail();

        return $model;
    }

    public function all(): Collection
    {
        return $this->model->newQuery()
            ->get();
    }

    /**
     * @return array<int, string>
     */
    public function allPluckedByIdAndTitle(): array
    {
        /** @var array<int, string> $menus */
        $menus = $this->all()
            ->pluck('title', 'id')
            ->toArray();

        return $menus;
    }
}
