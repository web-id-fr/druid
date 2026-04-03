<?php

namespace Webid\Druid\Filament\Resources\MenuResource\RelationManagers;

use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webid\Druid\Enums\MenuItemTarget;
use Webid\Druid\Models\Page;
use Webid\Druid\Models\Post;
use Webid\Druid\Repositories\MenuItemRepository;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        /** @var MenuItemRepository $menuItemRepository */
        $menuItemRepository = app()->make(MenuItemRepository::class);

        $targetOptions = [];
        foreach (MenuItemTarget::cases() as $target) {
            $targetOptions[$target->value] = $target->getLabel();
        }

        /** @var int $menuId */
        $menuId = $this->ownerRecord->getKey();

        return $schema
            ->schema([
                Select::make('parent_item_id')
                    ->label(__('Parent'))
                    ->placeholder(__('Select a parent item'))
                    ->options($menuItemRepository->allPluckedByIdAndLabel($menuId)
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
                    ->required(),
                Section::make('link')
                    ->schema([
                        Select::make('type')
                            ->label(__('Type'))
                            ->live()
                            ->required()
                            ->options(
                                [
                                    'page' => __('Link to an existing page'),
                                    'custom' => __('Custom URL'),
                                ],
                            ),
                        TextInput::make('custom_url')
                            ->url()
                            ->required(fn (Get $get) => $get('type') === 'custom')
                            ->visible(fn (Get $get) => $get('type') === 'custom'),
                        MorphToSelect::make('model')
                            ->label(__('Model'))
                            ->visible(fn (Get $get) => $get('type') === 'page')
                            ->required(fn (Get $get) => $get('type') === 'page')
                            ->types([
                                MorphToSelect\Type::make(Page::class)
                                    ->titleAttribute('title'),
                                MorphToSelect\Type::make(Post::class)
                                    ->titleAttribute('title'),
                            ]),

                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('label')
            ->columns([
                Tables\Columns\TextColumn::make('label'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                    \Filament\Actions\ForceDeleteBulkAction::make(),
                    \Filament\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
