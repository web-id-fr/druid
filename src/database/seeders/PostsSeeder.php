<?php

declare(strict_types=1);

namespace Webid\Druid\database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Models\Category;
use Webid\Druid\App\Models\Post;
use Webid\Druid\Database\Factories\CategoryFactory;
use Webid\Druid\Database\Factories\PostFactory;

class PostsSeeder extends Seeder
{
    public function run(): void
    {
        /** @var User $user */
        // @phpstan-ignore-next-line
        $user = User::query()->first();

        foreach ($this->getCategoriesStructure() as $categoryByLocale) {
            if (! isset($categoryByLocale[getDefaultLocaleKey()])) {
                return;
            }

            /** @var Category $category */
            $category = CategoryFactory::new()->create([
                ...$categoryByLocale[getDefaultLocaleKey()],
                'lang' => getDefaultLocaleKey(),
            ]);

            /** @var Collection<int, Post> $posts */
            $posts = PostFactory::new()
                ->count(5)
                ->forCategory($category)
                ->forUser($user)
                ->create();

            if (isMultilingualEnabled()) {
                foreach ($categoryByLocale as $categoryLocale => $categoryData) {
                    if ($categoryLocale === getDefaultLocaleKey()) {
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
                    'slug' => 'actualités',
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
