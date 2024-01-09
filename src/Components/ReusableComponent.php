<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\View\View;
use Webid\Druid\Models\ReusableComponent as ReusableComponentModel;
use Webid\Druid\Services\ComponentDisplayContentExtractor;
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
     * @param  array<string, mixed>  $data
     */
    public static function toBlade(array $data): View
    {
        $reusableComponent = self::getComponentFromData($data);
        /** @var ComponentDisplayContentExtractor $componentContentExtractor */
        $componentContentExtractor = app()->make(ComponentDisplayContentExtractor::class);

        return view('druid::components.text', [
            'content' => $componentContentExtractor->getContentFromBlocks($reusableComponent->content),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toSearchableContent(array $data): string
    {
        $reusableComponent = self::getComponentFromData($data);

        foreach ($reusableComponent->content as $simpleBlock) {

        }

        return '';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private static function getComponentFromData(array $data): ReusableComponentModel
    {
        $componentID = $data['reusable_component'];
        Assert::string($componentID);

        /** @var ReusableComponentModel $reusableComponent */
        $reusableComponent = ReusableComponentModel::query()->findOrFail(intval($componentID));

        return $reusableComponent;
    }
}
