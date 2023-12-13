<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\Select;

class ReusableComponent implements ComponentInterface
{
    public function blockSchema(): array
    {
        return [
            Select::make('reusable_component')
                ->label(__('Reusable component'))
                ->placeholder(__('Select a component'))
                ->options(\Webid\Druid\Models\ReusableComponent::all()->pluck('title', 'id'))
                ->searchable(),
        ];
    }

    function fieldName(): string
    {
        return 'reusable-component';
    }
}
