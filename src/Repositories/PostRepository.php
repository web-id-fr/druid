<?php

namespace Webid\Druid\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Webid\Druid\Enums\PostStatus;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Category;
use Webid\Druid\Models\Post;

class PostRepository
{
    private Post $model;

    public function __construct()
    {
        $this->model = Druid::Post();
    }

    /**
     * @param  array<string>  $relations
     */
    public function all(array $relations = []): Collection
    {
        return $this->model->all()->load($relations);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function findOrFailBySlugAndLang(string $slug, string $langCode): Post
    {
        /** @var Post $model */
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

    public function allFromDefaultLanguageWithoutTranslationForLang(string $lang, ?callable $queryModifier = null): Collection
    {
        $query = $this->model->newQuery()->where(['lang' => Druid::getDefaultLocale()])
            ->whereDoesntHave('translations', fn (Builder $query) => $query
                ->where('lang', $lang))
            ->where('status', PostStatus::PUBLISHED)
            ->orderBy('published_at', 'DESC');

        if ($queryModifier) {
            $queryModifier($query);
        }

        return $query->get();
    }

    /**
     * @param  array<string>  $relations
     */
    public function allPaginated(int $perPage, array $relations = [], ?callable $queryModifier = null): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with($relations)
            ->where('status', PostStatus::PUBLISHED)
            ->orderBy('published_at', 'DESC');

        if ($queryModifier) {
            $queryModifier($query);
        }

        return $query->paginate($perPage);
    }

    /**
     * @param  array<string>  $relations
     */
    public function allByCategoryPaginated(Category $category, int $perPage, array $relations = [], ?callable $queryModifier = null): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->whereRelation('categories', 'slug', $category->slug)
            ->with($relations)
            ->where('status', PostStatus::PUBLISHED)
            ->orderBy('published_at', 'DESC');

        if ($queryModifier) {
            $queryModifier($query);
        }

        return $query->paginate($perPage);
    }

    /**
     * @param  array<string>  $relations
     */
    public function allPaginatedByLang(int $perPage, string $lang, array $relations = [], ?callable $queryModifier = null): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with($relations)
            ->where('lang', $lang)
            ->where('status', PostStatus::PUBLISHED)
            ->orderBy('published_at', 'DESC');

        if ($queryModifier) {
            $queryModifier($query);
        }

        return $query->paginate($perPage);
    }

    /**
     * @param  array<string>  $relations
     */
    public function allByCategoryAndLangPaginated(Category $category, int $perPage, string $lang, array $relations = [], ?callable $queryModifier = null): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->whereRelation('categories', 'slug', $category->slug)
            ->with($relations)
            ->where('lang', $lang)
            ->where('status', PostStatus::PUBLISHED)
            ->orderBy('published_at', 'DESC');

        if ($queryModifier) {
            $queryModifier($query);
        }

        return $query->paginate($perPage);
    }

    public function findBySlug(string $slug, ?callable $queryModifier = null): Post
    {
        $query = $this->model->newQuery()
            ->where('slug', $slug)
            ->when(Druid::isMultilingualEnabled(), fn (Builder $query) => $query->where('lang', Druid::getCurrentLocaleKey()));

        if ($queryModifier) {
            $queryModifier($query);
        }

        return $query->firstOrFail();
    }

    public function replicate(Post $post): void
    {
        $replica = $post->replicate();
        $replica->slug = $post->incrementSlug($post->slug, $post->lang);
        $replica->title = __('[Copy]').' '.$post->title;
        $replica->status = PostStatus::DRAFT;
        $replica->save();
        $replica->categories()->attach($post->categories->pluck('id'));
        $replica->users()->attach($post->users->pluck('id'));
    }
}
