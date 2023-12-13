<?php

namespace Webid\Druid\Filament\Resources\ReusableComponentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webid\Druid\Filament\Resources\ReusableComponentResource;

class ListReusableComponents extends ListRecords
{
    protected static string $resource = ReusableComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
