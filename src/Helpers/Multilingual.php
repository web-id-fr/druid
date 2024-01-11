<?php

declare(strict_types=1);

if (! function_exists('isMultilingualEnabled')) {
    function isMultilingualEnabled(): bool
    {
        return config('cms.enable_multilingual_feature') === true;
    }
}

if (! function_exists('getDefaultLocaleKey')) {
    function getDefaultLocaleKey(): string
    {
        return getDefaultLocale()->value;
    }
}

if (! function_exists('getDefaultLocale')) {
    function getDefaultLocale(): \Webid\Druid\Enums\Langs
    {
        $defaultLanguage = config('cms.default_locale');
        Webmozart\Assert\Assert::string($defaultLanguage);

        return \Webid\Druid\Enums\Langs::from($defaultLanguage);
    }
}

if (! function_exists('getLocales')) {
    /**
     * @return array<string, array<string, string>>
     */
    function getLocales(): array
    {
        $locales = config('cms.locales');
        Webmozart\Assert\Assert::isArray($locales);

        return $locales;
    }
}
