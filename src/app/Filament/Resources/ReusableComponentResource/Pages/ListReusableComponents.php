<?php

namespace Webid\Druid\App\Filament\Resources\ReusableComponentResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Webid\Druid\App\Filament\Resources\ReusableComponentResource;
use Webid\Druid\App\Repositories\ReusableComponentsRepository;

class ListReusableComponents extends ListRecords
{
    protected static string $resource = ReusableComponentResource::class;

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

        /** @var ReusableComponentsRepository $reusableComponentsRepository */
        $reusableComponentsRepository = app(ReusableComponentsRepository::class);

        $tabs = [
            'all' => Tab::make(__('All'))->icon('heroicon-s-flag')
                ->badge($reusableComponentsRepository->countAll()),
        ];

        foreach (getLocales() as $localeKey => $localeData) {
            $tabs[$localeKey] = Tab::make($localeData['label'])
                ->modifyQueryUsing(fn (Builder $query) => $query->where('lang', $localeKey))
                ->badge($reusableComponentsRepository->countAllHavingLangCode($localeKey));
        }

        $noLangPagesCount = $reusableComponentsRepository->countAllWithoutLang();
        if ($noLangPagesCount > 0) {
            $tabs['no-lang'] = Tab::make(__('No lang'))->icon('heroicon-m-exclamation-triangle')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('lang'))
                ->badge($noLangPagesCount);
        }

        return $tabs;
    }
}
