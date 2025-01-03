<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Illuminate\Contracts\View\View;
use Webmozart\Assert\Assert;

class HintComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            Select::make('type')
                ->options([
                    'info' => __('Information'),
                    'warning' => __('Warning'),
                    'danger' => __('Danger'),
                ])
                ->default('information'),
            RichEditor::make('content')
                ->label(__('Content'))
                ->required(),
        ];
    }

    public static function fieldName(): string
    {
        return 'hint';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toBlade(array $data): View
    {
        return view('druid::components.hint', [
            'content' => $data['content'],
            'type' => $data['type'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toSearchableContent(array $data): string
    {
        $content = $data['content'] ?? '';
        Assert::string($content);

        return strip_tags($content);
    }

    public static function imagePreview(): string
    {
        return '/vendor/druid/cms/images/components/hint_component.png';
    }
}
