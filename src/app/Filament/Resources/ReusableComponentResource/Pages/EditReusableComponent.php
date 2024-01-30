<?php

namespace Webid\Druid\App\Filament\Resources\ReusableComponentResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Webid\Druid\App\Filament\Resources\ReusableComponentResource;

class EditReusableComponent extends EditRecord
{
    protected static string $resource = ReusableComponentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
