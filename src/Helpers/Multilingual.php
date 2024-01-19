<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Webid\Druid\Dto\LangLink;
use Webid\Druid\Services\LanguageSwitcher;

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

if (! function_exists('getCurrentLocale')) {
    function getCurrentLocale(): \Webid\Druid\Enums\Langs
    {
        $defaultLocale = getDefaultLocale();
        $langParam = request()->lang;
        if (! $langParam) {
            return $defaultLocale;
        }

        \Webmozart\Assert\Assert::string($langParam);

        $lang = \Webid\Druid\Enums\Langs::tryFrom($langParam);
        if (! $lang) {
            return $defaultLocale;
        }

        return $lang;
    }
}

if (! function_exists('getLanguageSwitcher')) {
    /**
     * @return Collection<LangLink>
     */
    function getLanguageSwitcher(): Collection
    {
        /** @var LanguageSwitcher $languageSwitcher */
        $languageSwitcher = app()->make(LanguageSwitcher::class);

        return $languageSwitcher->getLinks();
    }
}
