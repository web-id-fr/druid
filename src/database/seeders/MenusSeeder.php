<?php

declare(strict_types=1);

namespace Webid\Druid\database\seeders;

use Illuminate\Database\Seeder;
use Webid\Druid\App\Models\Menu;
use Webid\Druid\App\Models\Page;
use Webid\Druid\Database\Factories\MenuFactory;
use Webid\Druid\Database\Factories\MenuItemFactory;

class MenusSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getMenusStructure() as $menuStructureByLocale) {
            if (! isset($menuStructureByLocale[getDefaultLocaleKey()])) {
                return;
            }

            $menu = MenuFactory::new()->create([
                ...$menuStructureByLocale[getDefaultLocaleKey()],
                'lang' => getDefaultLocaleKey(),
            ]);

            Page::query()->where('lang', getDefaultLocaleKey())->each(function (Page $page) use ($menu) {
                /** @var Menu $menu */
                MenuItemFactory::new()->forExistingPage($page)->forMenu($menu)->create();
            });

            if (isMultilingualEnabled()) {
                foreach ($menuStructureByLocale as $menuLocale => $menuData) {
                    if ($menuLocale === getDefaultLocaleKey()) {
                        continue;
                    }

                    $menu = MenuFactory::new()->create([
                        ...$menuData,
                        'lang' => $menuLocale,
                    ]);

                    Page::query()->where('lang', getDefaultLocaleKey())->each(function (Page $page) use ($menu) {
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
