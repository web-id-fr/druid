<?php

namespace Webid\Druid\Models\Traits;

use Webid\Druid\Services\ComponentDisplayContentExtractor;

trait CanRenderContent
{
    public function renderContent(): string
    {
        /** @var ComponentDisplayContentExtractor $componentContentExtractor */
        $componentContentExtractor = app()->make(ComponentDisplayContentExtractor::class);

        return $componentContentExtractor->getContentFromBlocks($this->content);
    }
}
