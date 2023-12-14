<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\Select;

class ReusableComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            Select::make('reusable_component')
                ->label(__('Reusable component'))
                ->placeholder(__('Select a component'))
                ->options(\Webid\Druid\Models\ReusableComponent::all()->pluck('title', 'id'))
                ->searchable(),
        ];
    }

    public static function fieldName(): string
    {
        return 'reusable-component';
    }

    public static function toHtml(array $data): string
    {
        return '';
    }
}
