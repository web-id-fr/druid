<?php

declare(strict_types=1);

namespace Webid\Druid\Database\Factories;

use Awcodes\Curator\Database\Factories\MediaFactory as CuratorMediaFactory;
use Webid\Druid\Models\Media;

class MediaFactory extends CuratorMediaFactory
{
    protected $model = Media::class;
}
