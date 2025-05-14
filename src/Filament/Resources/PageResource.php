<?php

namespace Webid\Druid\Filament\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Webid\Druid\Enums\PageStatus;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Filament\Resources\PageResource\Pages;
use Webid\Druid\Models\Page;
use Webid\Druid\Repositories\PageRepository;
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
        /** @var PageRepository $pageRepository */
        $pageRepository = app()->make(PageRepository::class);

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
            Tables\Columns\IconColumn::make('disable_indexation')
                ->label(__('Disable indexation'))
                ->boolean(),
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
                Action::make('replicate')
                    ->label(__('Replicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->action(fn (Page $record) => $pageRepository->replicate($record))
                    ->requiresConfirmation(),
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
