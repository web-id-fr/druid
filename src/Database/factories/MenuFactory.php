<?php

namespace Webid\Druid\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Druid\Models\Menu;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'slug' => $this->faker->slug,
        ];
    }

    public function withItems(): self
    {
        return $this->afterCreating(function (Menu $menu) {
            MenuItemFactory::new()->forMenu($menu)->withCustomUrl()->create();
            MenuItemFactory::new()->forMenu($menu)->withParentItem()->create();;
            MenuItemFactory::new()->forMenu($menu)->withParentItem()->create();;
            MenuItemFactory::new()->forMenu($menu)->withPageItem()->create();
        });
    }
}
