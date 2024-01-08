<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\View\View;

class TextComponent implements ComponentInterface
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
        ];
    }

    public static function fieldName(): string
    {
        return 'text';
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function toBlade(array $data): View
    {
        return view('druid::components.text', [
            'content' => $data['content'],
        ]);
    }
}
