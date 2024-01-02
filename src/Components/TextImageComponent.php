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
                ->required(),
        ];
    }

    public static function fieldName(): string
    {
        return 'text-image';
    }

    public static function toHtml(array $data): string
    {
        return '<div class="text-image-component">
            <div class="text">'.$data['content'].'</div>
            <div class="image"><img src="'.$data['image'].'"></div>
            </div>';
    }
}
