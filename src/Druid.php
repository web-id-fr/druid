<?php

namespace Webid\Druid;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Webid\Druid\App\Dto\LangLink;
use Webid\Druid\App\Dto\Menu;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Filament\Pages\SettingsPage\SettingsInterface;
use Webid\Druid\App\Repositories\SettingsRepository;
use Webid\Druid\App\Services\LanguageSwitcher;
use Webid\Druid\App\Services\NavigationMenuManager;
use Webmozart\Assert\Assert;

class Druid
{
    public function isBlogModuleEnabled(): bool
    {
        return config('cms.enable_blog_module') === true;
    }

    public function isBlogDefaultRoutesEnabled(): bool
    {
        return config('cms.enable_default_blog_routes') === true;
    }

    public function getPostsPerPage(): int
    {
        /** @var int $perPage */
        $perPage = config('cms.blog.posts_per_page');

        return $perPage;
    }

    public function isMultilingualEnabled(): bool
    {
        return config('cms.enable_multilingual_feature') === true;
    }

    function getDefaultLocale(): Langs
    {
        $defaultLanguage = config('cms.default_locale');
        Assert::string($defaultLanguage);

        return Langs::from($defaultLanguage);
    }

    public function getDefaultLocaleKey(): string
    {
        return $this->getDefaultLocale()->value;
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function getLocales(): array
    {
        $locales = config('cms.locales');
        Assert::isArray($locales);

        return $locales;
    }

    public function getCurrentLocale(): Langs
    {
        $defaultLocale = $this->getDefaultLocale();
        $langParam = request()->lang;
        if (! $langParam) {
            return $defaultLocale;
        }

        Assert::isInstanceOf($langParam, Langs::class);

        return $langParam;
    }

    /**
     * @return Collection<LangLink>
     */
    public function getLanguageSwitcher(): Collection
    {
        /** @var LanguageSwitcher $languageSwitcher */
        $languageSwitcher = app()->make(LanguageSwitcher::class);

        return $languageSwitcher->getLinks();
    }

    public function getNavigationMenuBySlug(string $slug): Menu
    {
        /** @var NavigationMenuManager $navigationMenuManager */
        $navigationMenuManager = app()->make(NavigationMenuManager::class);

        return $navigationMenuManager->getBySlug($slug);
    }

    public function getNavigationMenuBySlugAndLang(string $slug, Langs $lang): Menu
    {
        /** @var NavigationMenuManager $navigationMenuManager */
        $navigationMenuManager = app()->make(NavigationMenuManager::class);

        return $navigationMenuManager->getBySlugAndLang($slug, $lang);
    }

    public function isSettingsPageEnabled(): bool
    {
        return config('cms.settings.enable_settings_page') === true;
    }

    public function settingsPage(): SettingsInterface
    {
        /** @var string $className */
        $className = config('cms.settings.settings_form');

        if (! class_exists($className)) {
            throw new \RuntimeException("$className does not exist.");
        }

        if (! is_subclass_of($className, SettingsInterface::class)) {
            throw new \RuntimeException("$className needs to implement SettingsInterface.");
        }

        return new $className();
    }

    public function getSettingByKey(string $key): ?Model
    {
        $settingsRepository = app(SettingsRepository::class);

        return $settingsRepository->findSettingByKeyName($key);
    }

    public function getSettings(): Collection
    {
        $settingsRepository = app(SettingsRepository::class);

        return $settingsRepository->all();
    }

    public function package_base_path(string $path = ''): string
    {
        $path = ltrim($path, '/');

        return __DIR__."/../../{$path}";
    }
}
