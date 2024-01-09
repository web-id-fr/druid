<?php

namespace Webid\Druid\Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Druid\Enums\PageStatus;

class PageFactory extends Factory
{
    protected $model = Page::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'slug' => $this->faker->slug,
            'status' => PageStatus::PUBLISHED->value,
            'content' => $this->fakeContent(),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fakeContent(): array
    {
        return [
            [
                'type' => 'text',
                'data' => [
                    'content' => '<p>' . $this->faker->text(300) . '</p>',
                ],
            ],
            [
                'type' => 'textImage',
                'data' => [
                    'content' => '<p>' . $this->faker->text(900) . '</p>',
                    'image' => 'placeholder-image.png'
                ],
            ],
            [
                'type' => 'text',
                'data' => [
                    'content' => '<h2>' . $this->faker->text(30) . '</h2><p>' . $this->faker->text(900) . '</p>',
                ],
            ],
        ];
    }
}
