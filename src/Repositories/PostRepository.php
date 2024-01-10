<?php

namespace Webid\Druid\Repositories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

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
}
