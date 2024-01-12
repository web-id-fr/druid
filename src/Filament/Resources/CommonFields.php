<?php

namespace Webid\Druid\Filament\Resources;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;

class CommonFields
{
    /**
     * @return array<int, Field>
     */
    public static function getCommonSeoFields(): array
    {
        return [
            TextInput::make('meta_title')
                ->label(__('Meta title')),
            RichEditor::make('meta_description')
                ->label(__('Meta description')),
            TextInput::make('meta_keywords')
                ->label(__('Meta keywords')),
            TextInput::make('opengraph_title')
                ->label(__('Opengraph title')),
            RichEditor::make('opengraph_description')
                ->label(__('Opengraph description')),
            FileUpload::make('opengraph_picture')
                ->label(__('Opengraph picture')),
            TextInput::make('opengraph_picture_alt')
                ->label(__('Opengraph picture alt')),
            Toggle::make('indexation')
                ->label(__('Indexation'))
                ->helperText(__('Allow search engines to index this page'))
                ->required(),
        ];
    }
}
