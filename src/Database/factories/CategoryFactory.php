<?php

namespace Webid\Druid\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\App\Models\Dummy\DummyCategory as Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word,
            'slug' => fake()->slug,
            'lang' => fake()->randomElement(['fr', 'en']),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Model $category): void {
            /** @var Category $category */
            if ($category->translation_origin_model_id) {
                return;
            }

            $category->update(['translation_origin_model_id' => $category->getKey()]);
        });
    }
}
