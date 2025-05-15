<?php

namespace Webid\Druid\Tests\Helpers;

use Webid\Druid\Services\LanguageSwitcher;

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
            'en' => [
                'label' => 'English',
                'homepage' => '/',
            ],
            'fr' => [
                'label' => 'Français',
                'homepage' => '/fr',
            ],
            'de' => [
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
