<?php

namespace Webid\Druid\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webid\Druid\App\Enums\MenuItemTarget;
use Webid\Druid\App\Models\Menu;
use Webid\Druid\App\Models\MenuItem;

class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'label' => $this->faker->name,
            'order' => $this->faker->numberBetween(0, 30),
            'parent_item_id' => null,
            'target' => MenuItemTarget::SELF->value,
        ];
    }

    public function forMenu(Menu $menu): self
    {
        return $this->state(function () use ($menu) {
            return [
                'menu_id' => $menu->getKey(),
            ];
        });
    }

    public function withCustomUrl(): self
    {
        return $this->state(function () {
            return [
                'custom_url' => $this->faker->url,
                'target' => MenuItemTarget::BLANK->value,
            ];
        });
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public function withPageItem(array $params = []): self
    {
        return $this->state(function () use ($params) {
            /** @var \Webid\Druid\App\Models\Page $page */
            $page = PageFactory::new()->create($params);

            return [
                'model_id' => $page->getKey(),
                'model_type' => $page->getMorphClass(),
            ];
        });
    }

    public function withParentItem(): self
    {
        return $this->afterCreating(
            // @phpstan-ignore-next-line
            fn (MenuItem $menuItem) => $menuItem->update(
                // @phpstan-ignore-next-line
                ['parent_item_id' => MenuItemFactory::new()->forMenu($menuItem->menu)->create()->getKey()]
            )
        );
    }
}
