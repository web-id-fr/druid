<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\View\View;

class TextImageComponent implements ComponentInterface
{
    /**
     * @return array<int, Field>
     */
    public static function blockSchema(): array
    {
        return [
            RichEditor::make('content')
                ->label(__('Content'))
                ->required(),
            FileUpload::make('image')
                ->label(__('Image'))
                ->required(),
        ];
    }

    public static function fieldName(): string
    {
        return 'textImage';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toBlade(array $data): View
    {
        return view('druid::components.text-image', [
            'content' => $data['content'],
            'image' => $data['image'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toSearchableContent(array $data): string
    {
        return '';
    }
}
