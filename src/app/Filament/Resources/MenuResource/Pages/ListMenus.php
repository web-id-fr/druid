<?php

namespace Webid\Druid\App\Filament\Resources\MenuResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webid\Druid\App\Filament\Resources\MenuResource;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
