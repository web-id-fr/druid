<?php

declare(strict_types=1);

namespace Webid\Druid\database\seeders;

use Illuminate\Database\Seeder;
use Webid\Druid\Database\Factories\MenuFactory;
use Webid\Druid\Database\Factories\MenuItemFactory;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Menu;
use Webid\Druid\Models\Page;
use Webmozart\Assert\Assert;

class MenusSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getMenusStructure() as $menuStructureByLocale) {
            if (! isset($menuStructureByLocale[Druid::getDefaultLocale()])) {
                return;
            }

            $pageModel = Druid::Page();

            /** @var Menu $menu */
            $menu = MenuFactory::new()->create([
                ...$menuStructureByLocale[Druid::getDefaultLocale()],
                'lang' => Druid::getDefaultLocale(),
            ]);

            $order = 1;
            $pageModel::query()
                ->where('lang', Druid::getDefaultLocale())
                ->orderBy('id')
                ->each(function (Page $page) use ($menu, &$order) {
                    /** @var Menu $menu */
                    MenuItemFactory::new()
                        ->forExistingPage($page)
                        ->forMenu($menu)
                        ->create(['order' => $order]);
                    $order++;
                });

            /** @phpstan-ignore-next-line */
            $blogUrl = config('app.url').'/';
            if (Druid::isMultilingualEnabled()) {
                $blogUrl .= Druid::getDefaultLocale().'/';
            }
            $blogUrl .= Druid::getBlogPrefix();

            MenuItemFactory::new()
                ->forCustomUrl($blogUrl, 'Blog')
                ->forMenu($menu)
                ->create(['order' => $order]);

            if (Druid::isMultilingualEnabled()) {
                foreach ($menuStructureByLocale as $menuLocale => $menuData) {
                    if ($menuLocale === Druid::getDefaultLocale()) {
                        continue;
                    }

                    /** @var Menu $menu */
                    $menu = MenuFactory::new()->create([
                        ...$menuData,
                        'lang' => $menuLocale,
                    ]);

                    $order = 1;
                    $pageModel::query()->where('lang', $menuLocale)
                        ->each(function (Page $page) use ($menu, &$order) {
                            /** @var Menu $menu */
                            MenuItemFactory::new()->forExistingPage($page)->forMenu($menu)->create(['order' => $order]);
                            $order++;
                        });

                    $appUrl = config('app.url');
                    Assert::string($appUrl);
                    MenuItemFactory::new()
                        ->forCustomUrl($appUrl.'/'.$menuLocale.'/'.Druid::getBlogPrefix(), 'Blog')
                        ->forMenu($menu)
                        ->create(['order' => $order]);
                }
            }
        }
    }

    /**
     * @return array<int, array<string, array<string, string>>>
     */
    protected function getMenusStructure(): array
    {
        return [
            [
                'en' => [
                    'title' => 'Main menu',
                    'slug' => 'main-menu',
                ],
                'fr' => [
                    'title' => 'Menu principal',
                    'slug' => 'main-menu',
                ],
                'de' => [
                    'title' => 'Hauptmenü',
                    'slug' => 'main-menu',
                ],
            ],
            [
                'en' => [
                    'title' => 'Footer menu',
                    'slug' => 'footer-menu',
                ],
                'fr' => [
                    'title' => 'Menu pied de page',
                    'slug' => 'footer-menu',
                ],
                'de' => [
                    'title' => 'Fußzeilenmenü',
                    'slug' => 'footer-menu',
                ],
            ],
        ];
    }
}
