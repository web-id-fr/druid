<?php

namespace Webid\Druid\Filament\Resources;

use App\Models\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Webid\Druid\Enums\PageStatus;
use Webid\Druid\Filament\Resources\PageResource\Pages;
use Webid\Druid\Services\Admin\FilamentComponentsService;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        /** @var FilamentComponentsService $filamentComponentService */
        $filamentComponentService = app(FilamentComponentsService::class);

        return $form
            ->schema(components: [
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('Content'))
                            ->schema([
                                TextInput::make('title')
                                    ->label(__('Title'))
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(
                                        fn (string $operation, $state, Set $set) => $operation === 'create'
                                            ? $set('slug', Str::slug($state)) : null
                                    )
                                    ->required(),
                                $filamentComponentService->getFlexibleContentFieldsForModel(Page::class)
                            ]),

                        Tabs\Tab::make(__('Parameters'))
                            ->schema([
                                TextInput::make('slug')
                                    ->label('Slug'),
                                Select::make('parent_page_id')
                                    ->label(__('Parent page'))
                                    ->placeholder(__('Select a parent page'))
                                    ->options(Page::all()->pluck('title', 'id'))
                                    ->searchable(),
                                Select::make('status')
                                    ->label(__('Status'))
                                    ->placeholder(__('Select a status'))
                                    ->options(PageStatus::class)
                                    ->default(PageStatus::PUBLISHED)
                                    ->required(),
                                Select::make('lang')
                                    ->label(__('Language'))
                                    ->options([
                                        1 => __('French'),
                                        0 => __('English'),
                                    ])
                                    ->placeholder(__('Select a language')),
                                DatePicker::make('published_at')
                                    ->label(__('Published at'))
                                    ->default(now())
                                    ->required(),
                            ])->columns(2),

                        Tabs\Tab::make(__('SEO'))
                            ->schema([
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
                                FileUpload::make('opengraph_picture')
                                    ->label(__('Opengraph picture')),
                                TextInput::make('opengraph_picture_alt')
                                    ->label(__('Opengraph picture alt')),
                                Toggle::make('indexation')
                                    ->label(__('Indexation'))
                                    ->helperText(__('Allow search engines to index this page'))
                                    ->required(),
                            ])->columns(2),
                    ])
                    ->activeTab(1)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title')),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => PageStatus::PUBLISHED,
                        'warning' => PageStatus::DRAFT,
                        'danger' => PageStatus::ARCHIVED,
                    ])
                    ->label(__('Status')),
                Tables\Columns\BooleanColumn::make('indexation')
                    ->boolean()
                    ->label(__('Indexation')),
                Tables\Columns\TextColumn::make('parent_page_id')
                    ->default('-')
                    ->label(__('Parent page')),
                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('Published at'))
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

    public static function getRecordSubNavigation(\Filament\Resources\Pages\Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewPage::class,
            Pages\EditPage::class,
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
