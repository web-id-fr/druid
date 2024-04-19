<?php

namespace Webid\Druid\App\Models\Traits;

use Webid\Druid\App\Services\ComponentDisplayContentExtractor;

trait CanRenderContent
{
    public function renderContent(): string
    {
        /** @var ComponentDisplayContentExtractor $componentContentExtractor */
        $componentContentExtractor = app()->make(ComponentDisplayContentExtractor::class);

        return $componentContentExtractor->getContentFromBlocks($this->content);
    }
}
