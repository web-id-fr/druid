<?php

namespace Webid\Druid\Database\Factories;

use App\Models\User;
use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Enums\PostStatus;
use Webid\Druid\Models\Category;
use Webid\Druid\Models\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'slug' => fake()->slug,
            'thumbnail_id' => Media::factory()->create()->getKey(),
            'thumbnail_alt' => fake()->words(3, true),
            'status' => PostStatus::PUBLISHED,
            'lang' => Langs::EN,
            'excerpt' => fake()->text,
            'content' => [
                [
                    'type' => 'textImage',
                    'data' => [
                        'content' => '<p>'.$this->faker->text(900).'</p>',
                        'image' => Media::factory()->create()->getKey(),
                    ],
                ],
                [
                    'type' => 'text',
                    'data' => [
                        'content' => '<h2>'.$this->faker->text(30).'</h2><p>'.$this->faker->text(900).'</p>',
                    ],
                ],
            ],
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
            'opengraph_title' => null,
            'opengraph_description' => null,
            'opengraph_picture' => null,
            'indexation' => 1,
            'follow' => 1,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Model $post): void {
            /** @var Post $post */
            if ($post->translation_origin_model_id) {
                return;
            }

            $post->update(['translation_origin_model_id' => $post->getKey()]);

            if ($post->categories()->count() === 0) {
                $category = CategoryFactory::new()->create();
                $post->categories()->attach($category);
            }
        });
    }

    public function asATranslationFrom(Post $post, Langs $lang): static
    {
        return $this->state(function (array $attributes) use ($lang, $post) {
            return [
                'lang' => $lang,
                'translation_origin_model_id' => $post->getKey(),
            ];
        });
    }

    public function forCategory(Category $category): static
    {
        return $this->afterCreating(function (Model $post) use ($category): void {
            /** @var Post $post */
            $post->categories()->attach($category);
            $post->save();
        });
    }

    public function forUser(User $user): static
    {
        return $this->afterCreating(function (Model $post) use ($user): void {
            /** @var Post $post */
            $post->users()->attach($user);
            $post->save();
        });
    }
}
