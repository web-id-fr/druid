<?php

namespace Webid\Druid\Filament\Resources;

use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webid\Druid\Filament\Resources\PageResource\Pages;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(components: [
                Section::make(__('Parameters'))->schema(components: [
                    Select::make('parent_page_id')
                        ->label(__('Parent page'))
                        ->placeholder(__('Select a parent page'))
                        ->options(Page::all()->pluck('title', 'id'))
                        ->searchable(),
                    Select::make('status')
                        ->label(__('Status'))
                        ->options([
                            1 => __('Published'),
                            0 => __('Draft'),
                        ])
                        ->placeholder(__('Select a status'))
                        ->required(),
                    TextInput::make('title')
                        ->label(__('Title'))
                        ->required(),
                    TextInput::make('slug')
                        ->label('Slug'),
                    Select::make('lang')
                        ->label(__('Language'))
                        ->options([
                            1 => __('French'),
                            0 => __('English'),
                        ])
                        ->placeholder(__('Select a language')),
                    DatePicker::make('published_at')
                        ->label(__('Published at')),
                ])->columns(2),

                Section::make(__('Content'))
                    ->schema(components: [Builder::make(__('Content'))->blocks([
                        Block::make('Texte')
                            ->schema([
                                Forms\Components\RichEditor::make('text')
                                    ->label('Texte')
                                    ->required(),
                            ]),
                        Block::make('Texte et Image')
                            ->schema([
                                Forms\Components\RichEditor::make('text')
                                    ->label('Texte')
                                    ->required(),
                                FileUpload::make('image')
                                    ->label('Image')
                                    ->image()
                                    ->required(),
                                TextInput::make('text_position')
                                    ->label('Position du texte')
                                    ->required(),
                            ]),
                        Block::make('Image')
                            ->schema([
                                FileUpload::make('image')
                                    ->label('Image')
                                    ->image()
                                    ->required(),
                            ]),
                    ])]),

                Section::make(__('SEO'))->schema([
                    TextInput::make('meta_title')
                        ->label(__('Meta title')),
                    Textarea::make('meta_description')
                        ->label(__('Meta description')),
                    TextInput::make('meta_keywords')
                        ->label(__('Meta keywords')),
                    TextInput::make('opengraph_title')
                        ->label(__('Opengraph title')),
                    Textarea::make('opengraph_description')
                        ->label(__('Opengraph description')),
                    TextInput::make('opengraph_picture')
                        ->label(__('Opengraph picture'))
                        ->maxLength(255),
                    TextInput::make('opengraph_picture_alt')
                        ->label(__('Opengraph picture alt')),
                    Toggle::make('indexation')
                        ->label(__('Indexation'))
                        ->helperText(__('Allow search engines to index this page'))
                        ->required(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('parent_page_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('indexation')
                    ->boolean(),
                Tables\Columns\TextColumn::make('opengraph_picture')
                    ->searchable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'view' => Pages\ViewPage::route('/{record}'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
