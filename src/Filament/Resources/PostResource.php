<?php

namespace Webid\Druid\Filament\Resources;

use App\Models\Post;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Webid\Druid\Enums\PostStatus;
use Webid\Druid\Filament\Resources\PostResource\Pages\EditPost;
use Webid\Druid\Filament\Resources\PostResource\Pages\ViewPost;
use Webid\Druid\Filament\Resources\PostResource\RelationManagers\CategoriesRelationManager;
use Webid\Druid\Models\Category;
use Webid\Druid\Services\Admin\FilamentComponentsService;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationGroup = 'Blog';

    public static function form(Form $form): Form
    {
        /** @var FilamentComponentsService $filamentComponentService */
        $filamentComponentService = app(FilamentComponentsService::class);

        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('Parameters'))
                            ->schema([
                                TextInput::make('title')
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
                                FileUpload::make('post_image')
                                    ->label(__('Image'))
                                    ->image()
                                    ->columnSpanFull(),
                                TextInput::make('post_image_alt')
                                    ->label(__('Image alt'))
                                    ->columnSpanFull(),
                                Select::make('status')
                                    ->label(__('Status'))
                                    ->options(PostStatus::class)
                                    ->default(PostStatus::PUBLISHED)
                                    ->required(),
                                Select::make('lang')
                                    ->label(__('Language'))
                                    ->options([
                                        1 => __('French'),
                                        0 => __('English'),
                                    ])
                                    ->placeholder(__('Select a language')),
                                DatePicker::make('publish_at')
                                    ->label(__('Published at'))
                                    ->default(now())
                                    ->required(),
                                Toggle::make('is_top_article')
                                    ->label(__('Top article'))
                                    ->helperText(__('Display this article in the top article section')),
                            ])->columns(2),

                        Tabs\Tab::make(__('Content'))
                            ->schema([
                                RichEditor::make('extrait')
                                    ->label(__('Extrait')),
                                $filamentComponentService->getFlexibleContentFieldsForModel(\App\Models\Page::class)
                            ]),

                        Tabs\Tab::make(__('SEO'))
                            ->schema([
                                TextInput::make('meta_title')
                                    ->label(__('Meta title'))
                                    ->columnSpanFull(),
                                TextInput::make('meta_description')
                                    ->label(__('Meta description'))
                                    ->columnSpanFull(),
                                TextInput::make('meta_keywords')
                                    ->label(__('Meta keywords'))
                                    ->columnSpanFull(),
                                TextInput::make('opengraph_title')
                                    ->label(__('Opengraph title'))
                                    ->columnSpanFull(),
                                TextInput::make('opengraph_description')
                                    ->label(__('Opengraph description'))
                                    ->columnSpanFull(),
                                FileUpload::make('opengraph_picture')
                                    ->label(__('Opengraph picture'))
                                    ->image()
                                    ->columnSpanFull(),
                                TextInput::make('opengraph_picture_alt')
                                    ->label(__('Opengraph picture alt'))
                                    ->columnSpanFull(),
                                Toggle::make('indexation')
                                    ->label(__('Indexation'))
                                    ->helperText(__('Allow search engines to index this page'))
                                    ->required(),
                                Toggle::make('follow')
                                    ->label(__('Follow'))
                                    ->helperText(__('Allow search engines to follow links on this page'))
                                    ->required(),
                            ])->columns(2),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
                Tables\Columns\ImageColumn::make('post_image'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => PostStatus::PUBLISHED,
                        'warning' => PostStatus::DRAFT,
                        'danger' => PostStatus::ARCHIVED,
                    ])
                    ->label(__('Status')),
                Tables\Columns\IconColumn::make('is_top_article')
                    ->label(__('Top article'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('indexation')
                    ->label(__('Indexation'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('follow')
                    ->label(__('Follow'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('publish_at')
                    ->label(__('Published at'))
                    ->dateTime()
                    ->sortable(),
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

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            ViewPost::class,
            EditPost::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            CategoriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => PostResource\Pages\ListPosts::route('/'),
            'create' => PostResource\Pages\CreatePost::route('/create'),
            'view' => PostResource\Pages\ViewPost::route('/{record}'),
            'edit' => PostResource\Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
