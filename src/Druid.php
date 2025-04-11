<?php

namespace Webid\Druid;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Webid\Druid\Dto\Menu;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Filament\Pages\SettingsPage\SettingsInterface;
use Webid\Druid\Models\Category;
use Webid\Druid\Models\MenuItem;
use Webid\Druid\Models\Page;
use Webid\Druid\Models\Post;
use Webid\Druid\Models\ReusableComponent;
use Webid\Druid\Models\Settings;
use Webid\Druid\Repositories\SettingsRepository;
use Webid\Druid\Services\LanguageSwitcher;
use Webid\Druid\Services\NavigationMenuManager;
use Webmozart\Assert\Assert;

class Druid
{
    public function getModel(string $model): string
    {
        if (! config("cms.models.$model")) {
            throw new \RuntimeException("Model $model not found in config file.");
        }

        /** @var string $model */
        $model = config("cms.models.$model");

        return $model;
    }

    public function User(): Authenticatable
    {
        /** @var Authenticatable $user */
        $user = new (config('cms.models.user'));

        return $user;
    }

    public function Page(): Page
    {
        /** @var Page $page */
        $page = new (config('cms.models.page'));

        return $page;
    }

    public function Post(): Post
    {
        /** @var Post $post */
        $post = new (config('cms.models.post'));

        return $post;
    }

    public function Category(): Category
    {
        /** @var Category $category */
        $category = new (config('cms.models.category'));

        return $category;
    }

    public function Menu(): \Webid\Druid\Models\Menu
    {
        /** @var \Webid\Druid\Models\Menu $menu */
        $menu = new (config('cms.models.menu'));

        return $menu;
    }

    public function MenuItem(): MenuItem
    {
        /** @var MenuItem $menuItem */
        $menuItem = new (config('cms.models.menu_item'));

        return $menuItem;
    }

    public function Settings(): Settings
    {
        /** @var Settings $settings */
        $settings = new (config('cms.models.settings'));

        return $settings;
    }

    public function ReusableComponent(): ReusableComponent
    {
        /** @var ReusableComponent $reusableComponent */
        $reusableComponent = new (config('cms.models.reusable_component'));

        return $reusableComponent;
    }

    public function menuItemsRelationManager(): string
    {
        /** @var string $menuItemRelationManager */
        $menuItemRelationManager = config('cms.menu.menu_items_relation_manager');

        return $menuItemRelationManager;
    }

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

    public function getDefaultLocale(): Langs
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
        $locales = Config::array('cms.locales');
        Assert::isArray($locales);

        // @phpstan-ignore-next-line
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

    public function isMenuModuleEnabled(): bool
    {
        return config('cms.enable_menu_module') === true;
    }

    /**
     * @return Collection<int|string, mixed>
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

        return new $className;
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

    public function isPageModuleEnabled(): bool
    {
        return config('cms.enable_page_module') === true;
    }

    public function package_base_path(string $path = ''): string
    {
        $path = ltrim($path, '/');

        return __DIR__."/../../{$path}";
    }
}
