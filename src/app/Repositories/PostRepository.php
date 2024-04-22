<?php

namespace Webid\Druid\App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Facades\Druid;
use Webid\Druid\App\Models\Category;
use Webid\Druid\App\Models\Post;

class PostRepository
{
    public function __construct(private readonly Post $model)
    {
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

    /**
     * @param  array<string>  $relations
     */
    public function allPaginated(int $perPage, array $relations = []): LengthAwarePaginator
    {
        return $this->model->newQuery()->with($relations)->paginate($perPage);
    }

    /**
     * @param  array<string>  $relations
     */
    public function allByCategoryPaginated(Category $category, int $perPage, array $relations = []): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->whereRelation('categories', 'slug', $category->slug)
            ->with($relations)
            ->paginate($perPage);
    }

    /**
     * @param  array<string>  $relations
     */
    public function allPaginatedByLang(int $perPage, Langs $lang, array $relations = []): LengthAwarePaginator
    {
        return $this->model->newQuery()->with($relations)->where('lang', $lang)->paginate($perPage);
    }

    /**
     * @param  array<string>  $relations
     */
    public function allByCategoryAndLangPaginated(Category $category, int $perPage, Langs $lang, array $relations = []): LengthAwarePaginator
    {
        return $this->model->newQuery()
            ->whereRelation('categories', 'slug', $category->slug)
            ->with($relations)
            ->where('lang', $lang)
            ->paginate($perPage);
    }
}
