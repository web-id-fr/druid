<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\RichEditor;

class TextComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            RichEditor::make('content')
                ->label(__('Content'))
                ->required()
        ];
    }

    public static function fieldName(): string
    {
        return 'text';
    }

    public static function toHtml(array $data): string
    {
        return '<div class="text-component">' . $data['content'] . '</div>';
    }
}
