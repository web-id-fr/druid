<?php

namespace Webid\Druid\Filament\Resources\CategoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Filament\Resources\CategoryResource;
use Webid\Druid\Repositories\CategoryRepository;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

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

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = app(CategoryRepository::class);

        $tabs = [
            'all' => Tab::make(__('All'))->icon('heroicon-s-flag')
                ->badge($categoryRepository->countAll()),
        ];

        foreach (getLocales() as $localeKey => $localeData) {
            $tabs[$localeKey] = Tab::make($localeData['label'])
                ->modifyQueryUsing(fn (Builder $query) => $query->where('lang', $localeKey))
                ->badge($categoryRepository->countAllHavingLang(Langs::from($localeKey)));
        }

        $noLangMenuCount = $categoryRepository->countAllWithoutLang();
        if ($noLangMenuCount > 0) {
            $tabs['no-lang'] = Tab::make(__('No lang'))->icon('heroicon-m-exclamation-triangle')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('lang'))
                ->badge($noLangMenuCount);
        }

        return $tabs;
    }
}
