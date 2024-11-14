<?php

namespace Webid\Druid\Filament\Resources;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
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
            Section::make(__('SEO Information'))
                ->schema([
                    TextInput::make('meta_title')
                        ->label(__('Meta title')),
                    RichEditor::make('meta_description')
                        ->label(__('Meta description')),
                    TextInput::make('meta_keywords')
                        ->label(__('Meta keywords')),
                    Toggle::make('indexation')
                        ->label(__('Indexation'))
                        ->helperText(__('Allow search engines to index this page'))
                        ->required(),
                ])
                ->columns(1),

            Section::make(__('Open Graph Information'))
                ->schema([
                    TextInput::make('opengraph_title')
                        ->label(__('Opengraph title')),
                    RichEditor::make('opengraph_description')
                        ->label(__('Opengraph description')),
                    CuratorPicker::make('opengraph_picture')
                        ->label(__('Opengraph picture'))
                        ->preserveFilenames(),
                    TextInput::make('opengraph_picture_alt')
                        ->label(__('Opengraph picture alt')),
                ])
                ->columns(1),
        ];
    }
}
