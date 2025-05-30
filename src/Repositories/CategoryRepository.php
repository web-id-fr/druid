<?php

namespace Webid\Druid\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Category;

class CategoryRepository
{
    private Category $model;

    public function __construct()
    {
        $this->model = Druid::Category();
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function findBySlug(string $slug): Category
    {
        /** @var Category $category */
        $category = $this->model->newQuery()
            ->where('slug', $slug)
            ->when(Druid::isMultilingualEnabled(), fn (Builder $query) => $query->where('lang', Druid::getCurrentLocaleKey()))
            ->firstOrFail();

        return $category;
    }

    public function allByLang(string $lang): Collection
    {
        return $this->model->newQuery()->where('lang', $lang)->get();
    }

    public function countAll(): int
    {
        return $this->model->newQuery()->count();
    }

    public function countAllHavingLang(string $lang): int
    {
        return $this->model->newQuery()->where('lang', $lang)->count();
    }

    public function countAllWithoutLang(): int
    {
        return $this->model->newQuery()->whereNull('lang')->count();
    }

    public function allFromDefaultLanguageWithoutTranslationForLang(string $lang): Collection
    {
        return $this->model->newQuery()->where(['lang' => Druid::getDefaultLocale()])
            ->whereDoesntHave('translations', fn (Builder $query) => $query
                ->where('lang', $lang))
            ->get();
    }

    public function categoryByLang(Category $category, string $lang): Model
    {
        return $this->model->newQuery()
            ->where([
                'slug' => $category->slug,
                'lang' => $lang,
            ])->firstOrFail();
    }
}
