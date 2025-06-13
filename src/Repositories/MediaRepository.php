<?php

namespace Webid\Druid\Repositories;

use Awcodes\Curator\Models\Media;

class MediaRepository
{
    public function __construct(
        private readonly Media $model,
    ) {}

    public function findById(int $id): Media
    {
        return $this->model->newQuery()->findOrFail($id);
    }
}
