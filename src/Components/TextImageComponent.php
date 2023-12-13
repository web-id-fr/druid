<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;

class TextImageComponent implements ComponentInterface
{
    public function blockSchema(): array
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

    function fieldName(): string
    {
        return 'text-image';
    }
}
