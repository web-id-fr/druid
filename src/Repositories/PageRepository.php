<?php

namespace Webid\Druid\Repositories;

use App\Models\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
}
