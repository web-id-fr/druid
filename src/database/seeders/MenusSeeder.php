<?php

declare(strict_types=1);

namespace Webid\Druid\database\seeders;

use Illuminate\Database\Seeder;
use Webid\Druid\Database\Factories\MenuFactory;
use Webid\Druid\Database\Factories\MenuItemFactory;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Menu;
use Webid\Druid\Models\Page;

class MenusSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getMenusStructure() as $menuStructureByLocale) {
            if (! isset($menuStructureByLocale[Druid::getDefaultLocaleKey()])) {
                return;
            }

            $menu = MenuFactory::new()->create([
                ...$menuStructureByLocale[Druid::getDefaultLocaleKey()],
                'lang' => Druid::getDefaultLocaleKey(),
            ]);

            Page::query()->where('lang', Druid::getDefaultLocaleKey())->each(function (Page $page) use ($menu) {
                /** @var Menu $menu */
                MenuItemFactory::new()->forExistingPage($page)->forMenu($menu)->create();
            });

            if (Druid::isMultilingualEnabled()) {
                foreach ($menuStructureByLocale as $menuLocale => $menuData) {
                    if ($menuLocale === Druid::getDefaultLocaleKey()) {
                        continue;
                    }

                    $menu = MenuFactory::new()->create([
                        ...$menuData,
                        'lang' => $menuLocale,
                    ]);

                    Page::query()->where('lang', Druid::getDefaultLocaleKey())->each(function (Page $page) use ($menu) {
                        /** @var Menu $menu */
                        MenuItemFactory::new()->forExistingPage($page)->forMenu($menu)->create();
                    });
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
                    'slug' => 'menu-principal',
                ],
                'de' => [
                    'title' => 'Hauptmenü',
                    'slug' => 'hauptmenu',
                ],
            ],
            [
                'en' => [
                    'title' => 'Footer menu',
                    'slug' => 'footer-menu',
                ],
                'fr' => [
                    'title' => 'Menu pied de page',
                    'slug' => 'menu-pied-de-page',
                ],
                'de' => [
                    'title' => 'Fußzeilenmenü',
                    'slug' => 'fußzeilenmenu',
                ],
            ],
        ];
    }
}
