<?php

namespace Webid\Druid\Filament\Resources\PageResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Filament\Resources\PageResource;
use Webid\Druid\Models\Page;
use Webid\Druid\Repositories\PageRepository;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->groups([Group::make('parent_page_id')
                ->getTitleFromRecordUsing(fn (Page $record): string => $record->parent?->title ? ucfirst($record->parent?->title) : 'Root')
                ->collapsible()
                ->label('Parent')])
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->orderBy('parent_page_id')
                ->with('children')
            );
    }

    public function getTabs(): array
    {
        if (! Druid::isMultilingualEnabled()) {
            return [];
        }

        /** @var PageRepository $pageRepository */
        $pageRepository = app(PageRepository::class);

        $tabs = [
            'all' => Tab::make(__('All'))->icon('heroicon-s-flag')
                ->badge($pageRepository->countAll()),
        ];

        foreach (Druid::getLocales() as $localeKey => $localeData) {
            $tabs[$localeKey] = Tab::make($localeData['label'])
                ->modifyQueryUsing(fn (Builder $query) => $query->where('lang', $localeKey))
                ->badge($pageRepository->countAllHavingLangCode($localeKey));
        }

        $noLangPagesCount = $pageRepository->countAllWithoutLang();
        if ($noLangPagesCount > 0) {
            $tabs['no-lang'] = Tab::make(__('No lang'))->icon('heroicon-m-exclamation-triangle')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('lang'))
                ->badge($noLangPagesCount);
        }

        return $tabs;
    }

    protected function paginateTableQuery(Builder $query): Paginator
    {
        return $query->simplePaginate(($this->getTableRecordsPerPage() === 'all') ? $query->count() : $this->getTableRecordsPerPage());
    }
}
