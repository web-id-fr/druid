<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;

class TextImageComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            RichEditor::make('content')
                ->label(__('Content'))
                ->required(),
            FileUpload::make('image')
                ->label(__('Image'))
                ->required()
        ];
    }

    public static function fieldName(): string
    {
        return 'textImage';
    }

    public static function toBlade(array $data): string
    {
        return view('druid::components.text-image', [
            'content' => $data['content'],
            'image' => $data['image']
        ]);
    }
}
