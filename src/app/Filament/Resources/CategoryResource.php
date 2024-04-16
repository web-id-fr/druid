<?php

namespace Webid\Druid\App\Filament\Resources;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Webid\Druid\App\Filament\Resources\CategoryResource\Pages\CreateCategory;
use Webid\Druid\App\Filament\Resources\CategoryResource\Pages\EditCategory;
use Webid\Druid\App\Filament\Resources\CategoryResource\Pages\ListCategories;
use Webid\Druid\App\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;
use Webid\Druid\App\Models\Category;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Parameters'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Title'))
                            ->live(onBlur: true)
                            ->afterStateUpdated(
                                fn (string $operation, $state, Set $set) => $operation === 'create'
                                    ? $set('slug', Str::slug($state)) : null
                            )
                            ->required(),
                        TextInput::make('slug')
                            ->label(__('Slug'))
                            ->required(),
                        Select::make('lang')
                            ->label(__('Language'))
                            ->options([
                                1 => __('French'),
                                0 => __('English'),
                            ])
                            ->placeholder(__('Select a language'))
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }

    public static function getRelations(): array
    {
        return [
            PostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return isBlogModuleEnable();
    }
}
