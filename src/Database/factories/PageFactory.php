<?php

namespace Webid\Druid\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Enums\PageStatus;
use Webid\Druid\App\Models\Dummy\DummyPage;

class PageFactory extends Factory
{
    protected $model = DummyPage::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'slug' => $this->faker->slug,
            'status' => PageStatus::PUBLISHED->value,
            'content' => $this->fakeContent(),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'lang' => 'en',
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Model $page): void {
            /** @var DummyPage $page */
            if ($page->translation_origin_model_id) {
                return;
            }

            $page->update(['translation_origin_model_id' => $page->getKey()]);
        });
    }

    public function asATranslationFrom(DummyPage $page, Langs $lang): static
    {
        return $this->state(function (array $attributes) use ($lang, $page) {
            return [
                'lang' => $lang,
                'translation_origin_model_id' => $page->getKey(),
            ];
        });
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
                    'content' => '<p>'.$this->faker->text(300).'</p>',
                ],
            ],
            [
                'type' => 'textImage',
                'data' => [
                    'content' => '<p>'.$this->faker->text(900).'</p>',
                    'image' => 'placeholder-image.png',
                ],
            ],
            [
                'type' => 'text',
                'data' => [
                    'content' => '<h2>'.$this->faker->text(30).'</h2><p>'.$this->faker->text(900).'</p>',
                ],
            ],
        ];
    }
}
