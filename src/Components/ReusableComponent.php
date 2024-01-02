<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\Select;
use Webid\Druid\Models\ReusableComponent as ReusableComponentModel;

class ReusableComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            Select::make('reusable_component')
                ->label(__('Reusable component'))
                ->placeholder(__('Select a component'))
                ->options(ReusableComponentModel::all()->pluck('title', 'id'))
                ->searchable(),
        ];
    }

    public static function fieldName(): string
    {
        return 'reusable-component';
    }

    public static function toBlade(array $data): string
    {
        /** @var ReusableComponentModel $reusableComponent */
        $reusableComponent = ReusableComponentModel::query()->findOrFail(intval($data['reusable_component']));

        return $reusableComponent->html_content;
    }
}
