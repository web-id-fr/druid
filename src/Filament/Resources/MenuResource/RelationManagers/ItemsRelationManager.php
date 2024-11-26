<?php

namespace Webid\Druid\Filament\Resources\MenuResource\RelationManagers;

use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Webid\Druid\Enums\MenuItemTarget;
use Webid\Druid\Models\Page;
use Webid\Druid\Models\Post;
use Webid\Druid\Repositories\MenuItemRepository;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        /** @var MenuItemRepository $menuItemRepository */
        $menuItemRepository = app()->make(MenuItemRepository::class);

        $targetOptions = [];
        foreach (MenuItemTarget::cases() as $target) {
            $targetOptions[$target->value] = $target->getLabel();
        }

        /** @var int $menuId */
        $menuId = $this->ownerRecord->getKey();

        return $form
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
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
