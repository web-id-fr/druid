<?php

namespace Webid\Druid\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Webid\Druid\Enums\PageStatus;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Page;

class PageRepository
{
    private Page $model;

    public function __construct()
    {
        $this->model = Druid::Page();
    }

    public function all(): Collection
    {
        return $this->model->all();
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
        $model = $this->model->newQuery()
            ->where('slug', $slug)
            ->firstOrFail();

        return $model;
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findOrFailBySlugAndLang(string $slug, string $langCode): Page
    {
        /** @var Page */
        return $this->model->newQuery()
            ->where([
                'slug' => $slug,
                'lang' => $langCode,
            ])
            ->firstOrFail();
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
        return $this->model->newQuery()->where(['lang' => Druid::getDefaultLocale()])
            ->whereDoesntHave('translations', fn (Builder $query) => $query
                ->where('lang', $lang))
            ->get();
    }

    public function findBySlug(string $slug): Page
    {
        /** @var Page $page */
        $page = $this->model->newQuery()
            ->where('slug', $slug)
            ->when(Druid::isMultilingualEnabled(), fn (Builder $query) => $query->where('lang', Druid::getCurrentLocale()))
            ->firstOrFail();

        return $page;
    }

    public function replicate(Page $page): void
    {
        $replica = $page->replicate();
        $replica->slug = $page->incrementSlug($page->slug, $page->lang);
        $replica->title = __('[Copy]').' '.$page->title;
        $replica->status = PageStatus::DRAFT;
        $replica->save();
    }
}
