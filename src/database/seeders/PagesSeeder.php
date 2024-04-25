<?php

declare(strict_types=1);

namespace Webid\Druid\database\seeders;

use Illuminate\Database\Seeder;
use Webid\Druid\Database\Factories\PageFactory;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Page;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getPagesStructure() as $pageStructure) {
            if (! isset($pageStructure[Druid::getDefaultLocaleKey()])) {
                return;
            }

            /** @var Page $mainPage */
            $mainPage = PageFactory::new()
                ->create([
                    'lang' => Druid::getDefaultLocaleKey(),
                    'title' => $pageStructure[Druid::getDefaultLocaleKey()]['title'],
                    'slug' => $pageStructure[Druid::getDefaultLocaleKey()]['slug'],
                ]);

            if (Druid::isMultilingualEnabled()) {
                foreach ($pageStructure as $locale => $structure) {
                    if ($locale === getDefaultLocaleKey()) {
                        continue;
                    }

                    PageFactory::new()->asATranslationFrom($mainPage, Langs::from($locale))
                        ->create([
                            'title' => $structure['title'],
                            'slug' => $structure['slug'],
                        ]);
                }
            }
        }
    }

    /**
     * @return array<int, array<string, array<string, string>>>
     */
    protected function getPagesStructure(): array
    {
        return [
            [
                'en' => [
                    'title' => 'Home',
                    'slug' => 'home',
                ],
                'fr' => [
                    'title' => 'Accueil',
                    'slug' => 'accueil',
                ],
                'de' => [
                    'title' => 'Hauptseite',
                    'slug' => 'hauptseite',
                ],
            ],
            [
                'en' => [
                    'title' => 'Our products',
                    'slug' => 'our-products',
                ],
                'fr' => [
                    'title' => 'Nos produits',
                    'slug' => 'nos-produits',
                ],
                'de' => [
                    'title' => 'Unsere Produkte',
                    'slug' => 'unsere-produits',
                ],
            ],
            [
                'en' => [
                    'title' => 'Who are we',
                    'slug' => 'who-are-we',
                ],
                'fr' => [
                    'title' => 'Qui sommes-nous',
                    'slug' => 'qui-sommes-nous',
                ],
                'de' => [
                    'title' => 'Wer sind wir',
                    'slug' => 'wer-sind-wir',
                ],
            ],
            [
                'en' => [
                    'title' => 'Contact us',
                    'slug' => 'contact-us',
                ],
                'fr' => [
                    'title' => 'Nous contacter',
                    'slug' => 'nous contacter',
                ],
                'de' => [
                    'title' => 'Kontaktiere uns',
                    'slug' => 'kontakt-uns',
                ],
            ],
        ];
    }
}
