<?php

namespace Webid\Druid\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\ReusableComponent;

class ReusableComponentsRepository
{
    private ReusableComponent $model;

    public function __construct()
    {
        $this->model = Druid::ReusableComponent();
    }

    /**
     * @param  array<string>  $relations
     */
    public function all(array $relations = []): Collection
    {
        return $this->model->all()->load($relations);
    }

    public function countAll(): int
    {
        return $this->model->newQuery()->count();
    }

    public function countAllHavingLangCode(string $lang): int
    {
        return $this->model->newQuery()->where('lang', $lang)->count();
    }

    public function countAllWithoutLang(): int
    {
        return $this->model->newQuery()->whereNull('lang')->count();
    }

    public function allExceptForPageId(?int $postId): Collection
    {
        return $this->model->newQuery()->whereNot($this->model->getKeyName(), $postId)->get();
    }

    public function allFromDefaultLanguageWithoutTranslationForLang(string $lang): Collection
    {
        return $this->model->newQuery()->where(['lang' => Druid::getDefaultLocale()])
            ->whereDoesntHave('translations', fn (Builder $query) => $query
                ->where('lang', $lang))
            ->get();
    }

    public function allForLang(string $lang): Collection
    {
        return $this->model->newQuery()->where('lang', $lang)->get();
    }
}
