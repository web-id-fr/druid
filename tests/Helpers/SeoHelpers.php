<?php

namespace Webid\Druid\Tests\Helpers;

trait SeoHelpers
{
    protected function globalDisableIndexation(): void
    {
        config()->set('cms.disable_robots_follow', true);
    }
}
