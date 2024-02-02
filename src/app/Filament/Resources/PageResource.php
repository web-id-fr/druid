<?php

namespace Webid\Druid\App\Filament\Resources;

use App\Models\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Webid\Druid\App\Enums\PageStatus;
use Webid\Druid\App\Filament\Resources\PageResource\Pages;
use Webid\Druid\App\Repositories\PageRepository;
use Webid\Druid\App\Services\Admin\FilamentComponentsService;
use Webmozart\Assert\Assert;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Pages';

    public static function form(Form $form): Form
    {
        /** @var FilamentComponentsService $filamentComponentService */
        $filamentComponentService = app(FilamentComponentsService::class);

        /** @var PageRepository $pageRepository */
        $pageRepository = app(PageRepository::class);

        $contentTab = [
            TextInput::make('title')
                ->label(__('Title'))
                ->live(onBlur: true)
                ->afterStateUpdated(
                    fn (string $operation, $state, Set $set) => $operation === 'create'
                        ? $set('slug', Str::slug($state)) : null
                )
                ->required(),
            $filamentComponentService->getFlexibleContentFieldsForModel(Page::class),
        ];

        $parametersTab = [
            TextInput::make('slug')
                ->label(__('Slug')),
            Select::make('parent_page_id')
                ->label(__('Parent page'))
                ->placeholder(__('Select a parent page'))
                // @phpstan-ignore-next-line
                ->options(fn (?Model $record): Collection => $pageRepository->allExceptForPageId($record?->getKey())->pluck('title', 'id'))
                ->searchable(),
            Select::make('status')
                ->label(__('Status'))
                ->placeholder(__('Select a status'))
                ->options(PageStatus::class)
                ->default(PageStatus::PUBLISHED)
                ->required(),
            DatePicker::make('published_at')
                ->label(__('Published at'))
                ->default(now())
                ->required(),
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
                        ->options(function (Get $get, ?Page $page) use ($pageRepository) {
                            $lang = $get('lang');
                            Assert::string($lang);

                            $allDefaultLanguagePages = $pageRepository->allFromDefaultLanguageWithoutTranslationForLang($lang)
                                // @phpstan-ignore-next-line
                                ->mapWithKeys(fn (Page $mapPage) => [$mapPage->getKey() => $mapPage->title]);

                            if ($page) {
                                $allDefaultLanguagePages->put($page->id, __('#No origin model'));
                            }

                            if ($page?->translationOriginModel->isNot($page)) {
                                $allDefaultLanguagePages->put($page->translationOriginModel->id, $page->translationOriginModel->title);
                            }

                            return $allDefaultLanguagePages;
                        })
                        ->searchable()
                        ->hidden(fn (Get $get): bool => ! $get('lang') || $get('lang') === getDefaultLocaleKey())
                        ->live(),
                ]
            );
        }

        return $form
            ->schema(components: [
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('Content'))->schema($contentTab),
                        Tabs\Tab::make(__('Parameters'))->schema($parametersTab)->columns(2),
                        Tabs\Tab::make(__('SEO'))->schema(CommonFields::getCommonSeoFields())->columns(2),
                    ])
                    ->activeTab(1)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\TextColumn::make('title')
                ->label(__('Title'))
                ->color('primary')
                ->url(
                    url: fn (Page $record) => $record->loadMissing(['parent'])->url(),
                    shouldOpenInNewTab: true
                )
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->colors([
                    'success' => PageStatus::PUBLISHED,
                    'warning' => PageStatus::DRAFT,
                    'danger' => PageStatus::ARCHIVED,
                ])
                ->label(__('Status')),
            Tables\Columns\IconColumn::make('indexation')
                ->boolean()
                ->label(__('Indexation')),
            Tables\Columns\TextColumn::make('parent_page_id')
                ->default('-')
                ->label(__('Parent page')),
            Tables\Columns\TextColumn::make('published_at')
                ->label(__('Published at')),
        ];

        if (isMultilingualEnabled()) {
            $columns[] = Tables\Columns\ViewColumn::make('translations')->view('admin.translations');
        }

        return $table
            ->columns($columns)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button()->outlined()->icon(''),
                Tables\Actions\DeleteAction::make(),
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

    public static function getNavigationBadge(): ?string
    {
        // @phpstan-ignore-next-line
        return static::$model::count();
    }
}
