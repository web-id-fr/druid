<?php

declare(strict_types=1);

namespace Webid\Druid\Models;

use Awcodes\Curator\Models\Media as CuratorMedia;

class Media extends CuratorMedia
{
    /** @var string */
    protected $table = 'media';
}
