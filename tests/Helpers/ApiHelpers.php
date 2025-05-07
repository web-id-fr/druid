<?php

namespace Webid\Druid\Tests\Helpers;

use Modules\RealEstate\Services\Api\Program\ProgramApiInteractor;
use Webid\Druid\Services\ContentRenderer\ApiRenderer;
use Webid\Druid\Services\ContentRenderer\ContentRenderer;

trait ApiHelpers
{
    protected function enableApiMode(): void
    {
        app()->bind(ContentRenderer::class, ApiRenderer::class);
    }
}
