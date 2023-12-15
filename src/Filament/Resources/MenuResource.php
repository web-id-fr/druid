<?php

namespace Webid\Druid\Filament\Resources;

use App\Models\Page;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webid\Druid\Models\Menu;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Menu'))
                    ->columns(1)
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->required(),

                        Builder::make('content')
                            ->blocks([
                                Builder\Block::make(__('external_url'))
                                    ->schema([
                                        TextInput::make('content')
                                            ->label(__('Extarnal Url'))
                                            ->required(),
                                    ]),

                                Builder\Block::make(__('page'))
                                    ->schema([
                                        Select::make('content')
                                            ->label(__('Page'))
                                            ->placeholder(__('Select a page'))
                                            ->options(
                                                Page::query()
                                                    ->get()
                                                    ->pluck('title', 'id')
                                                    ->toArray()
                                            ),
                                    ]),

                                Builder\Block::make(__('post'))
                                    ->schema([
                                        Select::make('content')
                                            ->label(__('Post'))
                                            ->placeholder(__('Select a post'))
                                            ->options(
                                                Page::query()
                                                    ->get()
                                                    ->pluck('title', 'id')
                                                    ->toArray()
                                            ),
                                    ]),
                            ])->blockNumbers(false),
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => MenuResource\Pages\ListMenus::route('/'),
            'create' => MenuResource\Pages\CreateMenu::route('/create'),
            'edit' => MenuResource\Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
