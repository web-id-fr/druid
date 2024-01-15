<?php

namespace Webid\Druid\Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Druid\Enums\PostStatus;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'slug' => fake()->slug,
            'post_image' => 'image.png',
            'post_image_alt' => fake()->words(3, true),
            'status' => PostStatus::PUBLISHED,
            'lang' => fake()->randomElement(['fr', 'en']),
            'excerpt' => fake()->text,
            'content' => [
                [
                    'type' => 'text',
                    'data' => [
                        'content' => $this->faker->text(300),
                    ],
                ],
            ],
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
            'opengraph_title' => null,
            'opengraph_description' => null,
            'opengraph_picture' => null,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Model $post): void {
            /** @var Page $post */
            if ($post->translation_origin_model_id) {
                return;
            }

            $post->update(['translation_origin_model_id' => $post->getKey()]);
        });
    }
}
