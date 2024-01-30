<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\App\Services\LanguageSwitcher;

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

    protected function setLocalesList(): void
    {
        config()->set('cms.locales', [
            \Webid\Druid\App\Enums\Langs::EN->value => [
                'label' => 'English',
                'homepage' => '/',
            ],
            \Webid\Druid\App\Enums\Langs::FR->value => [
                'label' => 'Français',
                'homepage' => '/fr',
            ],
            \Webid\Druid\App\Enums\Langs::DE->value => [
                'label' => 'German',
                'homepage' => '/de',
            ],
        ]);
    }

    protected function getLanguageSwitcher(): LanguageSwitcher
    {
        /** @var LanguageSwitcher $switcher */
        $switcher = app()->make(LanguageSwitcher::class);

        return $switcher;
    }
}
