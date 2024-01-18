<?php

namespace Webid\Druid\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Models\Menu;

class MenuRepository
{
    public function __construct(private readonly Menu $model)
    {
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findOrFailBySlugAndLang(string $slug, Langs $lang): Menu
    {
        /** @var Menu $model */
        $model = $this->model->newQuery()
            ->where('lang', $lang)
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

    public function countAll(): int
    {
        return $this->model->newQuery()->count();
    }

    public function countAllHavingLang(Langs $lang): int
    {
        return $this->model->newQuery()->where('lang', $lang)->count();
    }

    public function countAllWithoutLang(): int
    {
        return $this->model->newQuery()->whereNull('lang')->count();
    }

    public function allFromDefaultLanguageWithoutTranslationForLang(Langs $lang): Collection
    {
        return $this->model->newQuery()->where(['lang' => getDefaultLocale()])
            ->whereDoesntHave('translations', fn (Builder $query) => $query
                ->where('lang', $lang))
            ->get();
    }
}
