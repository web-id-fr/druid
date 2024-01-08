<?php

namespace Webid\Druid\Tests\Features;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;
use Webid\Druid\Dto\Menu;
use Webid\Druid\Services\NavigationMenuManager;
use Webid\Druid\Tests\Helpers\MenuCreator;

class NavigationMenuManagerTest extends TestCase
{
    private NavigationMenuManager $navigationMenuManager;

    use MenuCreator;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var NavigationMenuManager $navigationMenuManager */
        $navigationMenuManager = app(NavigationMenuManager::class);
        $this->navigationMenuManager = $navigationMenuManager;
    }

    /** @test */
    public function a_wrong_menu_slug_throws_an_exception(): void
    {
        try {
            $this->navigationMenuManager->getBySlug('inexisting-slug');
        } catch (ModelNotFoundException $e) {
            $this->assertTrue(true);

            return;
        }

        $this->fail('No Model not found exception raised');
    }

    /** @test */
    public function a_menu_dto_is_returned_when_menu_slug_exists(): void
    {
        $this->createMenuWithSlug('footer');
        $menuResource = $this->navigationMenuManager->getBySlug('footer');

        $this->assertNotEmpty($menuResource);
        $this->assertInstanceOf(Menu::class, $menuResource);
    }

    /** @test */
    public function menu_items_label_can_be_set_manually(): void
    {
        $menu = $this->createMenuWithSlug('footer');
        $this->addPageItemToMenu($menu, ['label' => 'Custom Label']);

        $menuResource = $this->navigationMenuManager->getBySlug('footer');
        $this->assertEquals($menuResource->items->first()->label, 'Custom Label');
    }

    /** @test */
    public function the_model_title_overrides_label_if_empty(): void
    {
        $menu = $this->createMenuWithSlug('footer');
        $menuItem = $this->addPageItemToMenu($menu, ['label' => null]);
        $this->addPageItemToMenu($menu, ['parent_item_id' => $menuItem->getKey()]);

        $menuResource = $this->navigationMenuManager->getBySlug('footer');
        $this->assertEquals($menuResource->items->first()->label, $menu->level0Items->first()->model->title);
    }

    /** @test */
    public function items_are_sorted_by_order_param_asc(): void
    {
        $menu = $this->createMenuWithSlug('footer');
        $menuItemOrder20 = $this->addPageItemToMenu($menu, ['order' => 20]);
        $menuItemNoOrder = $this->addPageItemToMenu($menu, ['order' => null]);
        $menuItemOrder5 = $this->addPageItemToMenu($menu, ['order' => 5]);

        $subMenuItemOrder5 = $this->addPageItemToMenu($menu, ['parent_item_id' => $menuItemOrder5->getKey(), 'order' => 5]);
        $subMenuItemNoOrder = $this->addPageItemToMenu($menu, ['parent_item_id' => $menuItemOrder5->getKey(), 'order' => null]);
        $subMenuItemOrder20 = $this->addPageItemToMenu($menu, ['parent_item_id' => $menuItemOrder5->getKey(), 'order' => 20]);

        $subMenuItemLevel2Order5 = $this->addPageItemToMenu($menu, ['parent_item_id' => $subMenuItemOrder5->getKey(), 'order' => 5]);
        $subMenuItemLevel2NoOrder = $this->addPageItemToMenu($menu, ['parent_item_id' => $subMenuItemOrder5->getKey(), 'order' => null]);
        $subMenuItemLevel2Order20 = $this->addPageItemToMenu($menu, ['parent_item_id' => $subMenuItemOrder5->getKey(), 'order' => 20]);

        $menuResource = $this->navigationMenuManager->getBySlug('footer');
        $this->assertEquals($menuResource->items->first()->id, $menuItemNoOrder->getKey());
        $this->assertEquals($menuResource->items->get(1)->id, $menuItemOrder5->getKey());
        $this->assertEquals($menuResource->items->get(2)->id, $menuItemOrder20->getKey());

        $this->assertEquals($menuResource->items->get(1)->children->first()->id, $subMenuItemNoOrder->getKey());
        $this->assertEquals($menuResource->items->get(1)->children->get(1)->id, $subMenuItemOrder5->getKey());
        $this->assertEquals($menuResource->items->get(1)->children->get(2)->id, $subMenuItemOrder20->getKey());

        $this->assertEquals($menuResource->items->get(1)->children->get(1)->children->first()->id, $subMenuItemLevel2NoOrder->getKey());
        $this->assertEquals($menuResource->items->get(1)->children->get(1)->children->get(1)->id, $subMenuItemLevel2Order5->getKey());
        $this->assertEquals($menuResource->items->get(1)->children->get(1)->children->get(2)->id, $subMenuItemLevel2Order20->getKey());
    }
}
