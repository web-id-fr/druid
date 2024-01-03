<?php

namespace Webid\Druid\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\Page;
use App\Models\Post;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webid\Druid\Enums\MenuItemTarget;
use Webid\Druid\Models\Menu;
use Webid\Druid\Models\MenuItem;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Menus';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $targetOptions = [];
        foreach (MenuItemTarget::cases() as $target) {
            $targetOptions[$target->value] = $target->getLabel();
        }

        return $form
            ->schema([
                Select::make('menu_id')
                    ->label(__('Menu'))
                    ->placeholder(__('Select a menu'))
                    ->options(
                        Menu::query()
                            ->get()
                            ->pluck('title', 'id')
                            ->toArray()
                    )
                    ->required(),
                Select::make('parent_item_id')
                    ->label(__('Parent'))
                    ->placeholder(__('Select a parent item'))
                    ->options(
                        MenuItem::query()
                            ->get()
                            ->pluck('label', 'id')
                            ->map(function ($label, $id) {
                                return $label ?? 'Item ID #' . $id;
                            })
                            ->toArray()
                    ),
                TextInput::make('order')
                    ->label(__('Order'))
                    ->numeric()
                    ->nullable()
                    ->default(0),
                Select::make('target')
                    ->label(__('Target'))
                    ->options($targetOptions)
                    ->default(MenuItemTarget::SELF->value),
                TextInput::make('label')
                    ->label(__('Label'))
                    ->nullable(),
                Section::make('link')
                    ->schema([
                        Select::make('type')
                            ->label(__('Type'))
                            ->live()
                            ->options(
                                [
                                    'page' => __('Link to an existing page'),
                                    'custom' => __('Custom URL'),
                                ],
                            ),
                        TextInput::make('custom_url')
                            ->url()
                            ->nullable()
                            ->visible(fn (Get $get) => $get('type') === 'custom'),
                        MorphToSelect::make('model')
                            ->label(__('Model'))
                            ->visible(fn (Get $get) => $get('type') === 'page')
                            ->types([
                                MorphToSelect\Type::make(Page::class)
                                    ->titleAttribute('title'),
                                MorphToSelect\Type::make(Post::class)
                                    ->titleAttribute('title'),
                            ]),

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('ID')),
                Tables\Columns\TextColumn::make('label')
                    ->label(__('Label')),
                Tables\Columns\TextColumn::make('menu_id')
                    ->label(__('Menu ID')),
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
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
