<?php

namespace Webid\Druid\Repositories;

use App\Models\Page;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostRepository
{
    public function __construct(private readonly Post $model)
    {
    }

    public function all(array $relations = []): Collection
    {
        return $this->model->all()->load($relations);
    }
}
