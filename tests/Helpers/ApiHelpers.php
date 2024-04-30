<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\Enums\RenderType;

trait ApiHelpers
{
    protected string $configKey = 'cms.views.type';

    protected function enableApiMode(): void
    {
        config()->set($this->configKey, RenderType::API->value);
    }

    protected function disableApiMode(): void
    {
        config()->set($this->configKey, RenderType::BLADE->value);
    }
}
