<?php

namespace Webid\Druid\App\Filament\Resources\ReusableComponentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webid\Druid\App\Filament\Resources\ReusableComponentResource;

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
