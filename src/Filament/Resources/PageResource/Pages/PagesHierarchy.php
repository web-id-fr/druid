<?php

namespace Webid\Druid\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Webid\Druid\Filament\Resources\PageResource;
use Webid\Druid\Repositories\PageRepository;

class PagesHierarchy extends Page
{
    protected static string $resource = PageResource::class;

    protected static string $view = 'druid::filament.pages.pages-hierarchy';

    protected static ?string $navigationGroup = 'Pages';

    public Collection $pages;

    public function mount(): void
    {
        /** @var PageRepository $pageRepository */
        $pageRepository = app(PageRepository::class);

        $this->pages = $pageRepository->allByHierarchy();
    }
}
