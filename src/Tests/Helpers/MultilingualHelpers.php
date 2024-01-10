<?php

namespace Webid\Druid\Tests\Helpers;

trait MultilingualHelpers
{
    protected function enableMultilingualFeature(): void
    {
        config()->set('cms.enable_multilingual_feature', true);
        $this->assertTrue(config('cms.enable_multilingual_feature'));
    }

    protected function disableMultilingualFeature(): void
    {
        config()->set('cms.enable_multilingual_feature', false);
        $this->assertFalse(config('cms.enable_multilingual_feature'));
    }
}
