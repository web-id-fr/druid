<?php

namespace Webid\Druid\Filament\Resources;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Filament\Resources\CategoryResource\Pages\CreateCategory;
use Webid\Druid\Filament\Resources\CategoryResource\Pages\EditCategory;
use Webid\Druid\Filament\Resources\CategoryResource\Pages\ListCategories;
use Webid\Druid\Filament\Resources\CategoryResource\RelationManagers\PostsRelationManager;
use Webid\Druid\Models\Category;
use Webid\Druid\Repositories\CategoryRepository;
use Webmozart\Assert\Assert;

class CategoryResource extends Resource
{
    public static function getModel(): string
    {
        return Druid::getModel('category');
    }

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = app(CategoryRepository::class);

        $schema = [
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
        ];

        if (Druid::isMultilingualEnabled()) {
            $schema = array_merge(
                $schema,
                [
                    'lang' => Select::make('lang')
                        ->label(__('Language'))
                        ->options(
                            collect(Druid::getLocales())->mapWithKeys(fn ($item, $key) => [$key => $item['label'] ?? __('No label')])
                        )
                        ->required()
                        ->live()
                        ->placeholder(__('Select a language')),
                    'translation_origin_model_id' => Select::make('translation_origin_model_id')
                        ->label(__('Translation origin'))
                        ->options(function (Get $get, ?Category $category) use ($categoryRepository) {
                            $lang = $get('lang');
                            Assert::string($lang);

                            $allDefaultLanguagePosts = $categoryRepository->allFromDefaultLanguageWithoutTranslationForLang($lang)
                                // @phpstan-ignore-next-line
                                ->mapWithKeys(fn (Category $mapCatagory) => [$mapCatagory->getKey() => $mapCatagory->name]);

                            if ($category) {
                                $allDefaultLanguagePosts->put($category->id, __('#No origin model'));
                            }

                            if ($category?->translationOriginModel?->isNot($category)) {
                                $allDefaultLanguagePosts->put($category->translationOriginModel->id, $category->translationOriginModel->name);
                            }

                            return $allDefaultLanguagePosts;
                        })
                        ->searchable()
                        ->hidden(fn (Get $get): bool => ! $get('lang') || $get('lang') === Druid::getDefaultLocale())
                        ->live(),
                ]
            );
        }

        return $form
            ->schema([
                Section::make(__('Parameters'))
                    ->schema($schema)
                    ->columns(2),
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
        return Druid::isBlogModuleEnabled();
    }
}
