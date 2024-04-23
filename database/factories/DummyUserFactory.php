<?php

namespace Webid\Druid\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Druid\Models\Dummy\DummyUser;

class DummyUserFactory extends Factory
{
    protected $model = DummyUser::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => bcrypt('passwd'),
        ];
    }
}
