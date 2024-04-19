<?php

namespace Webid\Druid\App\Filament\Resources;

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
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Facades\Druid;
use Webid\Druid\App\Filament\Resources\MenuResource\RelationManagers\ItemsRelationManager;
use Webid\Druid\App\Models\Menu;
use Webid\Druid\App\Repositories\MenuRepository;
use Webmozart\Assert\Assert;

class MenuResource extends Resource
{
    protected static ?string $model = Menu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Menus';

    public static function form(Form $form): Form
    {
        /** @var MenuRepository $menuRepository */
        $menuRepository = app(MenuRepository::class);

        $parametersTab = [
            TextInput::make('title')
                ->label(__('Title'))
                ->live(debounce: 500)
                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug(strval($state))))
                ->required(),
            TextInput::make('slug')
                ->label(__('Slug'))
                ->required(),
        ];

        if (Druid::isMultilingualEnabled()) {
            $parametersTab = array_merge(
                $parametersTab,
                [
                    Select::make('lang')
                        ->label(__('Language'))
                        ->options(
                            collect(Druid::getLocales())->mapWithKeys(fn ($item, $key) => [$key => $item['label'] ?? __('No label')])
                        )
                        ->live()
                        ->placeholder(__('Select a language')),
                    Select::make('translation_origin_model_id')
                        ->label(__('Translation origin model'))
                        ->placeholder(__('Is a translation of...'))
                        ->options(function (Get $get, ?Menu $menu) use ($menuRepository) {
                            $lang = $get('lang');
                            Assert::string($lang);

                            $allDefaultLanguageMenus = $menuRepository->allFromDefaultLanguageWithoutTranslationForLang(Langs::from($lang))
                                // @phpstan-ignore-next-line
                                ->mapWithKeys(fn (Menu $mapMenu) => [$mapMenu->getKey() => $mapMenu->title]);

                            if ($menu) {
                                $allDefaultLanguageMenus->put($menu->id, __('#No origin model'));
                            }

                            if ($menu?->translationOriginModel?->isNot($menu)) {
                                $allDefaultLanguageMenus->put($menu->translationOriginModel->id, $menu->translationOriginModel->title);
                            }

                            return $allDefaultLanguageMenus;
                        })
                        ->searchable()
                        ->hidden(fn (Get $get): bool => ! $get('lang') || $get('lang') === Druid::getDefaultLocaleKey())
                        ->live(),
                ]
            );
        }

        return $form
            ->schema([
                Section::make(__('Menu'))
                    ->columns(1)
                    ->schema($parametersTab),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\TextColumn::make('title')
                ->label(__('Title')),
        ];

        if (Druid::isMultilingualEnabled()) {
            $columns[] = Tables\Columns\ViewColumn::make('translations')->view('admin.menu.translations');
        }

        return $table
            ->columns($columns)
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
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => MenuResource\Pages\ListMenus::route('/'),
            'create' => MenuResource\Pages\CreateMenu::route('/create'),
            'edit' => MenuResource\Pages\EditMenu::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // @phpstan-ignore-next-line
        return static::$model::count();
    }
}
