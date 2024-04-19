<?php

namespace Webid\Druid\App\Filament\Resources\MenuResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\View\View;
use Webid\Druid\App\Filament\Resources\MenuResource;
use Webid\Druid\App\Models\Menu;
use Webid\Druid\App\Services\NavigationMenuManager;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getFooter(): ?View
    {
        /** @var Menu $menu */
        $menu = $this->record;

        /** @var NavigationMenuManager $navigationMenuManager */
        $navigationMenuManager = app()->make(NavigationMenuManager::class);

        /** @var int $menuId */
        $menuId = $menu->getKey();

        return view('druid::admin.menu-preview', ['menu' => $navigationMenuManager->getById($menuId)]);
    }
}
