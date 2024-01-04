<?php

namespace Webid\Druid\Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

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
}
