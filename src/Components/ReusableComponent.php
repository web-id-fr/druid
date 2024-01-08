<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\View\View;
use Webid\Druid\Models\ReusableComponent as ReusableComponentModel;
use Webmozart\Assert\Assert;

class ReusableComponent implements ComponentInterface
{
    /**
     * @return array<int, Field>
     */
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

    /**
     * @param array<string, mixed> $data
     */
    public static function toBlade(array $data): View
    {
        $componentID = $data['reusable_component'];
        Assert::integer($componentID);

        /** @var ReusableComponentModel $reusableComponent */
        $reusableComponent = ReusableComponentModel::query()->findOrFail($componentID);

        return view($reusableComponent->html_content);
    }
}
