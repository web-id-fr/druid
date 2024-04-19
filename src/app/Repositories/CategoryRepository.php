<?php

namespace Webid\Druid\App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Facades\Druid;
use Webid\Druid\App\Models\Category;

class CategoryRepository
{
    public function __construct(private readonly Category $model)
    {
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function allByLang(Langs $lang): Collection
    {
        return $this->model->newQuery()->where('lang', $lang)->get();
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
        return $this->model->newQuery()->where(['lang' => Druid::getDefaultLocale()])
            ->whereDoesntHave('translations', fn (Builder $query) => $query
                ->where('lang', $lang))
            ->get();
    }

    public function categoryByLang(Category $category, Langs $lang): Model
    {
        return $this->model->newQuery()
            ->where([
                'slug' => $category->slug,
                'lang' => $lang,
            ])->firstOrFail();
    }
}
