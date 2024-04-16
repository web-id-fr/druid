<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Webid\Druid\App\Dto\LangLink;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Services\LanguageSwitcher;
use Webmozart\Assert\Assert;

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
    function getDefaultLocale(): Langs
    {
        $defaultLanguage = config('cms.default_locale');
        Assert::string($defaultLanguage);

        return Langs::from($defaultLanguage);
    }
}

if (! function_exists('getLocales')) {
    /**
     * @return array<string, array<string, string>>
     */
    function getLocales(): array
    {
        $locales = config('cms.locales');
        Assert::isArray($locales);

        return $locales;
    }
}

if (! function_exists('getCurrentLocale')) {
    function getCurrentLocale(): Langs
    {
        $defaultLocale = getDefaultLocale();
        $langParam = request()->lang;
        if (! $langParam) {
            return $defaultLocale;
        }

        $langParam = Langs::from($langParam);

        Assert::isInstanceOf($langParam, Langs::class);

        return $langParam;
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
