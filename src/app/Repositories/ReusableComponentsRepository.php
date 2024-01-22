<?php

namespace Webid\Druid\App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Models\ReusableComponent;

class ReusableComponentsRepository
{
    public function __construct(private readonly ReusableComponent $model)
    {
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
        return $this->model->newQuery()->where(['lang' => getDefaultLocale()])
            ->whereDoesntHave('translations', fn (Builder $query) => $query
                ->where('lang', $lang))
            ->get();
    }

    public function allForLang(Langs $lang): Collection
    {
        return $this->model->newQuery()->where('lang', $lang)->get();
    }
}
