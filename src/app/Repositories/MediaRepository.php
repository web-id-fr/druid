<?php

namespace Webid\Druid\App\Repositories;

class MediaRepository
{
    public function __construct(
        // @phpstan-ignore-next-line
        private readonly \Awcodes\Curator\Models\Media $model,
    ) {
    }

    // @phpstan-ignore-next-line
    public function findById(int $id): \Awcodes\Curator\Models\Media
    {
        // @phpstan-ignore-next-line
        return $this->model->newQuery()->findOrFail($id);
    }
}
