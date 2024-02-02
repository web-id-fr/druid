<?php

namespace Webid\Druid\App\Filament\Resources\PageResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Webid\Druid\App\Filament\Resources\PageResource;
use Webid\Druid\App\Repositories\PageRepository;

class ListPages extends ListRecords
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        if (! isMultilingualEnabled()) {
            return [];
        }

        /** @var PageRepository $pageRepository */
        $pageRepository = app(PageRepository::class);

        $tabs = [
            'all' => Tab::make(__('All'))->icon('heroicon-s-flag')
                ->badge($pageRepository->countAll()),
        ];

        foreach (getLocales() as $localeKey => $localeData) {
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
}
