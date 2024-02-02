<?php

namespace Webid\Druid\App\Filament\Resources\MenuResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Filament\Resources\MenuResource;
use Webid\Druid\App\Repositories\MenuRepository;

class ListMenus extends ListRecords
{
    protected static string $resource = MenuResource::class;

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

        /** @var MenuRepository $menuRepository */
        $menuRepository = app(MenuRepository::class);

        $tabs = [
            'all' => Tab::make(__('All'))->icon('heroicon-s-flag')
                ->badge($menuRepository->countAll()),
        ];

        foreach (getLocales() as $localeKey => $localeData) {
            $tabs[$localeKey] = Tab::make($localeData['label'])
                ->modifyQueryUsing(fn (Builder $query) => $query->where('lang', $localeKey))
                ->badge($menuRepository->countAllHavingLang(Langs::from($localeKey)));
        }

        $noLangMenuCount = $menuRepository->countAllWithoutLang();
        if ($noLangMenuCount > 0) {
            $tabs['no-lang'] = Tab::make(__('No lang'))->icon('heroicon-m-exclamation-triangle')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('lang'))
                ->badge($noLangMenuCount);
        }

        return $tabs;
    }
}
