<?php

namespace Webid\Druid\Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Models\ReusableComponent;

class ReusableComponentFactory extends Factory
{
    protected $model = ReusableComponent::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'content' => $this->fakeContent(),
            'lang' => 'en',
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Model $page): void {
            /** @var Page $page */
            if ($page->translation_origin_model_id) {
                return;
            }

            $page->update(['translation_origin_model_id' => $page->getKey()]);
        });
    }

    public function asATranslationFrom(ReusableComponent $reusableComponent, Langs $lang): static
    {
        return $this->state(function (array $attributes) use ($lang, $reusableComponent) {
            return [
                'lang' => $lang,
                'translation_origin_model_id' => $reusableComponent->getKey(),
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
                'type' => 'text',
                'data' => [
                    'content' => '<h2>'.$this->faker->text(30).'</h2><p>'.$this->faker->text(900).'</p>',
                ],
            ],
        ];
    }
}
