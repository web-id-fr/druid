<?php

namespace Webid\Druid\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Models\Menu;

class MenuFactory extends Factory
{
    protected $model = Menu::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->name,
            'slug' => $this->faker->slug,
            'lang' => Langs::EN->value,
        ];
    }

    public function withItems(): self
    {
        // @phpstan-ignore-next-line
        return $this->afterCreating(function (Menu $menu) {
            MenuItemFactory::new()->forMenu($menu)->withCustomUrl()->create();
            MenuItemFactory::new()->forMenu($menu)->withParentItem()->create();
            MenuItemFactory::new()->forMenu($menu)->withParentItem()->create();
            MenuItemFactory::new()->forMenu($menu)->withPageItem()->create();
        });
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Model $menu): void {
            /** @var Menu $menu */
            if ($menu->translation_origin_model_id) {
                return;
            }

            $menu->update(['translation_origin_model_id' => $menu->getKey()]);
        });
    }

    public function asATranslationFrom(Menu $menu, Langs $lang): static
    {
        return $this->state(function (array $attributes) use ($lang, $menu) {
            return [
                'lang' => $lang,
                'translation_origin_model_id' => $menu->getKey(),
            ];
        });
    }
}
