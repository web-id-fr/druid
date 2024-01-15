<?php

namespace Webid\Druid\Filament\Resources\MenuResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\View\View;
use Webid\Druid\Filament\Resources\MenuResource;
use Webid\Druid\Models\Menu;
use Webid\Druid\Services\NavigationMenuManager;

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

        return view('druid::admin.menu-preview', ['menu' => $navigationMenuManager->getBySlugAndLang($menu->slug, $menu->lang)]);
    }
}
