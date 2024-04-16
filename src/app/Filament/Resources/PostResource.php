<?php

namespace Webid\Druid\App\Filament\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Webid\Druid\App\Enums\PostStatus;
use Webid\Druid\App\Filament\Resources\PostResource\RelationManagers\CategoriesRelationManager;
use Webid\Druid\App\Filament\Resources\PostResource\RelationManagers\UsersRelationManager;
use Webid\Druid\App\Models\Post;
use Webid\Druid\App\Repositories\PostRepository;
use Webid\Druid\App\Services\Admin\FilamentComponentsService;
use Webmozart\Assert\Assert;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        /** @var FilamentComponentsService $filamentComponentService */
        $filamentComponentService = app(FilamentComponentsService::class);

        /** @var PostRepository $postsRepository */
        $postsRepository = app(PostRepository::class);

        $contentTab = [
            TextInput::make('title')
                ->label(__('Title'))
                ->live(onBlur: true)
                ->afterStateUpdated(
                    fn (string $operation, $state, Set $set) => $operation === 'create'
                        ? $set('slug', Str::slug($state)) : null
                )
                ->required(),
            RichEditor::make('excerpt')
                ->label(__('excerpt')),
            $filamentComponentService->getFlexibleContentFieldsForModel(Post::class),
        ];

        $parametersTab = [
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
            DatePicker::make('published_at')
                ->label(__('Published at'))
                ->default(now())
                ->required(),
            TextInput::make('slug')
                ->label(__('Slug'))
                ->required(),
            Toggle::make('is_top_article')
                ->label(__('Top article'))
                ->helperText(__('Display this article in the top article section')),
        ];

        if (isMultilingualEnabled()) {
            $parametersTab = array_merge(
                $parametersTab,
                [
                    Select::make('lang')
                        ->label(__('Language'))
                        ->options(
                            collect(getLocales())->mapWithKeys(fn ($item, $key) => [$key => $item['label'] ?? __('No label')])
                        )
                        ->live()
                        ->placeholder(__('Select a language')),
                    Select::make('translation_origin_model_id')
                        ->label(__('Translation origin model'))
                        ->placeholder(__('Is a translation of...'))
                        ->options(function (Get $get, ?Post $post) use ($postsRepository) {
                            $lang = $get('lang');
                            Assert::string($lang);

                            $allDefaultLanguagePosts = $postsRepository->allFromDefaultLanguageWithoutTranslationForLang($lang)
                                // @phpstan-ignore-next-line
                                ->mapWithKeys(fn (Post $mapPost) => [$mapPost->getKey() => $mapPost->title]);

                            if ($post) {
                                $allDefaultLanguagePosts->put($post->id, __('#No origin model'));
                            }

                            if ($post?->translationOriginModel?->isNot($post)) {
                                $allDefaultLanguagePosts->put($post->translationOriginModel->id, $post->translationOriginModel->title);
                            }

                            return $allDefaultLanguagePosts;
                        })
                        ->searchable()
                        ->hidden(fn (Get $get): bool => ! $get('lang') || $get('lang') === getDefaultLocaleKey())
                        ->live(),
                ]
            );
        }

        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('Content'))->schema($contentTab),
                        Tabs\Tab::make(__('Parameters'))->schema($parametersTab)->columns(2),
                        Tabs\Tab::make(__('SEO'))->schema(CommonFields::getCommonSeoFields())->columns(2),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\TextColumn::make('title')
                ->label(__('Title'))
                ->color('primary')
                ->url(
                    url: fn (Post $record) => url($record->loadMissing('categories')->fullUrlPath()),
                    shouldOpenInNewTab: true
                )
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
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
            Tables\Columns\TextColumn::make('published_at')
                ->label(__('Published at'))
                ->dateTime()
                ->sortable(),
        ];

        if (isMultilingualEnabled()) {
            $columns[] = Tables\Columns\ViewColumn::make('translations')->view('admin.post.translations');
        }

        return $table
            ->columns($columns)
            ->actions([
                Tables\Actions\EditAction::make()->button()->outlined()->icon(''),
                Tables\Actions\DeleteAction::make(),
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
            CategoriesRelationManager::class,
            UsersRelationManager::class,
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

    public static function getNavigationBadge(): ?string
    {
        // @phpstan-ignore-next-line
        return static::$model::count();
    }

    public static function canAccess(): bool
    {
        return isBlogModuleEnable();
    }
}
