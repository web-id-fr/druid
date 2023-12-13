<?php

namespace Webid\Druid\Filament\Resources\ReusableComponentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webid\Druid\Filament\Resources\ReusableComponentResource;

class ViewReusableComponent extends ViewRecord
{
    protected static string $resource = ReusableComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
