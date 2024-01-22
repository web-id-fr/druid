<?php

declare(strict_types=1);

namespace Webid\Druid\App\Providers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Webid\Druid\App\Dto\LangLink;
use Webid\Druid\App\Dto\Menu;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Services\LanguageSwitcher;
use Webid\Druid\App\Services\NavigationMenuManager;

class ComposerServiceProvider extends ServiceProvider
{
    private ?Menu $mainMenu = null;

    private ?Collection $languageSwitcherLinks = null;

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $view->with('mainMenu', $this->getMainMenu());

            if (isMultilingualEnabled()) {
                $view->with('languageSwitcher', $this->getLanguageSwitcher());
                $view->with('currentLocale', getCurrentLocale());
            }
        });
    }

    private function getMainMenu(): ?Menu
    {
        if ($this->mainMenu) {
            return $this->mainMenu;
        }

        /** @var NavigationMenuManager $menuManager */
        $menuManager = app()->make(NavigationMenuManager::class);
        try {
            $mainMenu = $menuManager->getBySlugAndLang('main', Langs::EN);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        $this->mainMenu = $mainMenu;

        return $mainMenu;
    }

    /**
     * @return Collection<LangLink>
     */
    private function getLanguageSwitcher(): Collection
    {
        if ($this->languageSwitcherLinks) {
            return $this->languageSwitcherLinks;
        }

        /** @var LanguageSwitcher $languageSwitcher */
        $languageSwitcher = app()->make(LanguageSwitcher::class);

        $this->languageSwitcherLinks = $languageSwitcher->getLinks();

        return $this->languageSwitcherLinks;
    }
}
