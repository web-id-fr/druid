<?php

namespace Webid\Druid\App\Components;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\View\View;

class TextImageComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            RichEditor::make('content')
                ->label(__('Content'))
                ->required(),
            CuratorPicker::make('image')
                ->label(__('Image'))
                ->preserveFilenames()
        ];
    }

    public static function fieldName(): string
    {
        return 'textImage';
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function toBlade(array $data): View
    {
        return view('druid::components.text-image', [
            'content' => $data['content'],
            'image' => $data['image'],
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function toSearchableContent(array $data): string
    {
        return '';
    }
}
