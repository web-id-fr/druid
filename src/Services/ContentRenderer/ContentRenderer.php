<?php

namespace Webid\Druid\Services\ContentRenderer;

interface ContentRenderer
{
    /**
     * @param  array<string, mixed>  $context
     */
    public function render(string $view, array $context): mixed;
}
