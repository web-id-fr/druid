<?php

namespace Webid\Druid\Filament\Resources;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Filament\Resources\ReusableComponentResource\Pages\CreateReusableComponent;
use Webid\Druid\Filament\Resources\ReusableComponentResource\Pages\EditReusableComponent;
use Webid\Druid\Filament\Resources\ReusableComponentResource\Pages\ListReusableComponents;
use Webid\Druid\Filament\Resources\ReusableComponentResource\Pages\ViewReusableComponent;
use Webid\Druid\Models\ReusableComponent;
use Webid\Druid\Repositories\ReusableComponentsRepository;
use Webid\Druid\Services\Admin\FilamentComponentsService;
use Webmozart\Assert\Assert;

class ReusableComponentResource extends Resource
{
    protected static ?string $model = ReusableComponent::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Pages';

    public static function form(Form $form): Form
    {
        /** @var FilamentComponentsService $filamentComponentService */
        $filamentComponentService = app(FilamentComponentsService::class);

        /** @var ReusableComponentsRepository $reusableComponentsRepository */
        $reusableComponentsRepository = app(ReusableComponentsRepository::class);

        $contentTab = [
            TextInput::make('title')
                ->label(__('Title'))
                ->required(),
            $filamentComponentService->getFlexibleContentFieldsForModel(ReusableComponent::class),
        ];

        $parametersTab = [];
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
                        ->options(function (Get $get, ?ReusableComponent $reusableComponent) use ($reusableComponentsRepository) {
                            $lang = $get('lang');
                            Assert::string($lang);

                            $allDefaultLanguageComponents = $reusableComponentsRepository->allFromDefaultLanguageWithoutTranslationForLang($lang)
                                // @phpstan-ignore-next-line
                                ->mapWithKeys(fn (ReusableComponent $mapReusableComponent) => [$mapReusableComponent->getKey() => $mapReusableComponent->title]);

                            if ($reusableComponent) {
                                $allDefaultLanguageComponents->put($reusableComponent->id, __('#No origin model'));
                            }

                            if ($reusableComponent?->translationOriginModel?->isNot($reusableComponent)) {
                                $allDefaultLanguageComponents->put($reusableComponent->translationOriginModel->id, $reusableComponent->translationOriginModel->title);
                            }

                            return $allDefaultLanguageComponents;
                        })
                        ->searchable()
                        ->hidden(fn (Get $get): bool => ! $get('lang') || $get('lang') === Druid::getDefaultLocaleKey())
                        ->live(),
                ]
            );
        }

        $tabs = [
            Tabs\Tab::make(__('Content'))->schema($contentTab),
        ];

        if (Druid::isMultilingualEnabled()) {
            $tabs[] = Tabs\Tab::make(__('Parameters'))->schema($parametersTab)->columns(2);
        }

        return $form
            ->schema(components: [
                Tabs::make('Tabs')
                    ->tabs($tabs)
                    ->activeTab(1)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReusableComponents::route('/'),
            'create' => CreateReusableComponent::route('/create'),
            'view' => ViewReusableComponent::route('/{record}'),
            'edit' => EditReusableComponent::route('/{record}/edit'),
        ];
    }
}
