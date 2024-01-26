<?php

namespace Webid\Druid\App\Repositories;

use App\Model\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class PageRepository
{
    public function __construct(private readonly Page $model)
    {
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findOrFail(int|string $pageId): Page
    {
        /** @var Page $model */
        $model = $this->model->newQuery()->findOrFail($pageId);

        return $model;
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findOrFailBySlug(string $slug): Page
    {
        /** @var Page $model */
        $model = $this->model->newQuery()->where('slug', $slug)->firstOrFail();

        return $model;
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findOrFailBySlugAndLang(string $slug, string $langCode): Page
    {
        /** @var Page $model */
        $model = $this->model->newQuery()
            ->where([
                'slug' => $slug,
                'lang' => $langCode,
            ])->firstOrFail();

        return $model;
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

    public function allExceptForPageId(?int $pageId): Collection
    {
        return $this->model->newQuery()->whereNot($this->model->getKeyName(), $pageId)->get();
    }

    public function allFromDefaultLanguageWithoutTranslationForLang(string $lang): Collection
    {
        return $this->model->newQuery()->where(['lang' => getDefaultLocale()])
            ->whereDoesntHave('translations', fn (Builder $query) => $query
                ->where('lang', $lang))
            ->get();
    }
}
