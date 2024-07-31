<?php

namespace Webid\Druid\Repositories;

class MediaRepository
{
    public function __construct(
        private readonly \Awcodes\Curator\Models\Media $model,
    ) {}

    public function findById(int $id): \Awcodes\Curator\Models\Media
    {
        return $this->model->newQuery()->findOrFail($id);
    }
}
