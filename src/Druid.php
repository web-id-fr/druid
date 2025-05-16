<?php

namespace Webid\Druid;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Webid\Druid\Dto\Lang;
use Webid\Druid\Dto\Menu;
use Webid\Druid\Models\Category;
use Webid\Druid\Models\MenuItem;
use Webid\Druid\Models\Page;
use Webid\Druid\Models\Post;
use Webid\Druid\Models\ReusableComponent;
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

    public function getBlogPrefix(): string
    {
        /** @var string $prefix */
        $prefix = config('cms.blog.prefix');

        return $prefix;
    }

    public function getBlogRootUrlForLang(string $locale): string
    {
        return $this->isMultilingualEnabled() ? url(route('posts.multilingual.index', ['lang' => $locale])) : url(route('posts.index'));
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

    public function getDefaultLocale(): string
    {
        $defaultLanguage = config('cms.default_locale');
        Assert::string($defaultLanguage);

        return $defaultLanguage;
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

    public function getCurrentLocaleKey(): string
    {
        $defaultLocale = $this->getDefaultLocale();
        $segments = request()->segments();
        if (! isset($segments[0]) || ! is_string($segments[0])) {
            return $defaultLocale;
        }

        Assert::string($segments[0]);

        return $segments[0];
    }

    public function getCurrentLocale(): Lang
    {
        $currentLocaleKey = $this->getCurrentLocaleKey();
        $localeLabel = Config::string('cms.locales.'.$currentLocaleKey.'.label');

        return Lang::make($currentLocaleKey, $localeLabel);
    }

    public function getHomeUrlForLocal(string $locale): string
    {
        return '/'.$locale;
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

    public function getNavigationMenuBySlugAndLang(string $slug, string $lang): Menu
    {
        /** @var NavigationMenuManager $navigationMenuManager */
        $navigationMenuManager = app()->make(NavigationMenuManager::class);

        return $navigationMenuManager->getBySlugAndLang($slug, $lang);
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
