<?php

namespace Webid\Druid\Filament\Resources;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
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
                    Textarea::make('meta_description')
                        ->label(__('Meta description')),
                    TextInput::make('meta_keywords')
                        ->label(__('Meta keywords')),
                    Toggle::make('disable_indexation')
                        ->label(__('Disable indexation'))
                        ->helperText(__('Disable search engines indexation for this page')),
                ])
                ->columns(1),

            Section::make(__('Open Graph Information'))
                ->schema([
                    TextInput::make('opengraph_title')
                        ->label(__('Opengraph title')),
                    Textarea::make('opengraph_description')
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
