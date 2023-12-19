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

    public static function toBlade(array $data): string
    {
        return view('druid::components.text', [
            'content' => $data['content']
        ]);
    }
}
