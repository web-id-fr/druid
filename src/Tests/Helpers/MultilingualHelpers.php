<?php

namespace Webid\Druid\Tests\Helpers;

trait MultilingualHelpers
{
    protected function enableMultilingualFeature(): void
    {
        config()->set('cms.enable_multilingual_feature', true);
    }

    protected function disableMultilingualFeature(): void
    {
        config()->set('cms.enable_multilingual_feature', false);
    }

    protected function setDefaultLanguageKey(string $languageKey): void
    {
        config()->set('cms.default_locale', $languageKey);
    }
}
