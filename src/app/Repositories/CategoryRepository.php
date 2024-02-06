<?php

namespace Webid\Druid\App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Models\Category;

class CategoryRepository
{
    public function __construct(private readonly Category $model)
    {
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
