<?php

declare(strict_types=1);

namespace Webid\Druid\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Webid\Druid\Database\Factories\CategoryFactory;
use Webid\Druid\Database\Factories\PostFactory;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Category;
use Webid\Druid\Models\Post;
use Webmozart\Assert\Assert;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        $user = Druid::User()->query()->first();
        Assert::notNull($user);

        foreach ($this->getCategoriesStructure() as $categoryByLocale) {
            if (! isset($categoryByLocale[Druid::getDefaultLocaleKey()])) {
                return;
            }

            /** @var Category $category */
            $category = CategoryFactory::new()->create([
                ...$categoryByLocale[Druid::getDefaultLocaleKey()],
                'lang' => Druid::getDefaultLocaleKey(),
            ]);

            /** @var Collection<int, Post> $posts */
            $posts = PostFactory::new()
                ->count(5)
                ->forCategory($category)
                ->forUser($user)
                ->create();

            if (Druid::isMultilingualEnabled()) {
                foreach ($categoryByLocale as $categoryLocale => $categoryData) {
                    if ($categoryLocale === Druid::getDefaultLocaleKey()) {
                        continue;
                    }

                    /** @var Category $translatedCategory */
                    $translatedCategory = CategoryFactory::new()->create([
                        ...$categoryData,
                        'lang' => $categoryLocale,
                        'translation_origin_model_id' => $category->getKey(),
                    ]);

                    foreach ($posts as $post) {
                        PostFactory::new()
                            ->forCategory($translatedCategory)
                            ->asATranslationFrom($post, Langs::from($categoryLocale))
                            ->create();
                    }
                }
            }
        }
    }

    /**
     * @return array<int, array<string, array<string, string>>>
     */
    protected function getCategoriesStructure(): array
    {
        return [
            [
                'en' => [
                    'name' => 'News',
                    'slug' => 'news',
                ],
                'fr' => [
                    'name' => 'Actualités',
                    'slug' => 'actualites',
                ],
                'de' => [
                    'name' => 'Nachricht',
                    'slug' => 'nachricht',
                ],
            ],
            [
                'en' => [
                    'name' => 'Decryption',
                    'slug' => 'decryption',
                ],
                'fr' => [
                    'name' => 'Décryptage',
                    'slug' => 'decryptage',
                ],
                'de' => [
                    'name' => 'Entschlüsselung',
                    'slug' => 'entschlusselung',
                ],
            ],
            [
                'en' => [
                    'name' => 'Sponsored article',
                    'slug' => 'sponsored-article',
                ],
                'fr' => [
                    'name' => 'Article sponsorisé',
                    'slug' => 'article-sponsorise',
                ],
                'de' => [
                    'name' => 'Gesponserter Artikel',
                    'slug' => 'gesponserter-artikel',
                ],
            ],
        ];
    }
}
