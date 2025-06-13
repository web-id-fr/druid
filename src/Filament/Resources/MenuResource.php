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
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\Menu;
use Webid\Druid\Repositories\MenuRepository;
use Webmozart\Assert\Assert;

class MenuResource extends Resource
{
    public static function getModel(): string
    {
        return Druid::getModel('menu');
    }

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
                        ->required()
                        ->default(Druid::getDefaultLocale())
                        ->placeholder(__('Select a language')),
                    Select::make('translation_origin_model_id')
                        ->label(__('Translation origin model'))
                        ->placeholder(__('Is a translation of...'))
                        ->options(function (Get $get, ?Menu $menu) use ($menuRepository) {
                            $lang = $get('lang');
                            Assert::string($lang);

                            $allDefaultLanguageMenus = $menuRepository->allFromDefaultLanguageWithoutTranslationForLang($lang)
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
                        ->hidden(fn (Get $get): bool => ! $get('lang') || $get('lang') === Druid::getDefaultLocale())
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
            $columns[] = Tables\Columns\ViewColumn::make('translations')->view('druid::admin.menu.translations');
        }

        return $table
            ->columns($columns)
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            Druid::menuItemsRelationManager(),
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

    public static function canAccess(): bool
    {
        return Druid::isMenuModuleEnabled();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
