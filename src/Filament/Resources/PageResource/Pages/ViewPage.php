<?php

namespace Webid\Druid\Filament\Resources\PageResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webid\Druid\Filament\Resources\PageResource;

class ViewPage extends ViewRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
