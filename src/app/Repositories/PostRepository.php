<?php

namespace Webid\Druid\App\Repositories;

use App\Model\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        return $this->model->newQuery()->where(['lang' => getDefaultLocale()])
            ->whereDoesntHave('translations', fn (Builder $query) => $query
                ->where('lang', $lang))
            ->get();
    }
}
