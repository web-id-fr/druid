<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\RichEditor;

class TextComponent implements ComponentInterface
{
    public function blockSchema(): array
    {
        return [
            RichEditor::make('content')
                ->label(__('Content'))
                ->required()
        ];
    }

    function fieldName(): string
    {
        return 'text';
    }
}
