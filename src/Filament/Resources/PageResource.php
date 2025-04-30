<?php

namespace Webid\Druid\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Enums\PageStatus;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Filament\Resources\PageResource\Pages;
use Webid\Druid\Models\Page;
use Webid\Druid\Services\Admin\FilamentFieldsBuilders\FilamentPageFieldsBuilder;

class PageResource extends Resource
{
    public static function getModel(): string
    {
        return Druid::getModel('page');
    }

    protected static ?string $modelLabel = 'Page';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Pages';

    public static function form(Form $form): Form
    {
        /** @var FilamentPageFieldsBuilder $fieldsBuilder */
        $fieldsBuilder = app()->make(FilamentPageFieldsBuilder::class);

        return $form->schema(components: $fieldsBuilder->getFields());
    }

    public static function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\TextColumn::make('title')
                ->label(__('Title'))
                ->color('primary')
                ->url(
                    url: fn (Page $record) => $record->url(),
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
            Tables\Columns\IconColumn::make('follow')
                ->boolean()
                ->label(__('Follow')),
            Tables\Columns\TextColumn::make('parent_page_id')
                ->default('-')
                ->label(__('Parent page')),
            Tables\Columns\TextColumn::make('published_at')
                ->label(__('Published at'))
                ->sortable(),
        ];

        if (Druid::isMultilingualEnabled()) {
            $columns[] = Tables\Columns\ViewColumn::make('translations')->view('druid::admin.page.translations');
        }

        return $table
            ->query(fn () => Page::query()->with('parent'))
            ->columns($columns)
            ->defaultSort('published_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()->button()->outlined()->icon(''),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ReplicateAction::make()
                    ->form(fn (Form $form) => static::form($form->model(static::$model))->columns(2))
                    ->fillForm(function (Page $record): array {
                        /** @var string $slug */
                        $slug = $record['slug'];
                        /** @var Langs|null $lang */
                        $lang = $record['lang'];

                        if (Page::where('slug', $slug)->when(Druid::isMultilingualEnabled(), function ($query) use ($lang) {
                            $query->where('lang', $lang);
                        })->exists()) {
                            $slug = $record->incrementSlug($slug, $lang);
                        }

                        $record['slug'] = $slug;

                        return $record->toArray();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly()
            ->striped()
            ->deferLoading();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'hierarchy' => Pages\PagesHierarchy::route('/hierarchy'),
            'create' => Pages\CreatePage::route('/create'),
            'view' => Pages\ViewPage::route('/{record}'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return Druid::isPageModuleEnabled();
    }
}
