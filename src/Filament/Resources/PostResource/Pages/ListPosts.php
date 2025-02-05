<?php

namespace Webid\Druid\Filament\Resources\PostResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Filament\Resources\PostResource;
use Webid\Druid\Http\Resources\MediaResource;
use Webid\Druid\Repositories\MediaRepository;
use Webid\Druid\Repositories\PostRepository;
use Webid\Druid\Services\ComponentDisplayContentExtractor;

class ListPosts extends ListRecords
{
    use HasPreviewModal;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        if (! Druid::isMultilingualEnabled()) {
            return [];
        }

        /** @var PostRepository $postRepository */
        $postRepository = app(PostRepository::class);

        $tabs = [
            'all' => Tab::make(__('All'))->icon('heroicon-s-flag')
                ->badge($postRepository->countAll()),
        ];

        foreach (Druid::getLocales() as $localeKey => $localeData) {
            $tabs[$localeKey] = Tab::make($localeData['label'])
                ->modifyQueryUsing(fn (Builder $query) => $query->where('lang', $localeKey))
                ->badge($postRepository->countAllHavingLangCode($localeKey));
        }

        $noLangPagesCount = $postRepository->countAllWithoutLang();
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

    protected function getActions(): array
    {
        return [
            PreviewAction::make(),
        ];
    }

    protected function getPreviewModalView(): ?string
    {
        return 'preview.blog';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'post';
    }

    protected function mutatePreviewModalData(array $data): array
    {
        /** @var ComponentDisplayContentExtractor $componentDisplayContentExtractor */
        $componentDisplayContentExtractor = app()->make(ComponentDisplayContentExtractor::class);
        /** @var MediaRepository $mediaRepository */
        $mediaRepository = app(MediaRepository::class);

        $data['content'] = $componentDisplayContentExtractor->getContentFromBlocks($data['post']['content']);
        $data['image'] = $data['post']['thumbnail_id']
            ? MediaResource::make($mediaRepository->findById($data['post']['thumbnail_id']))->resolve()
            : null;
        $data['image']['id'] = $data['post']['thumbnail_id'];

        return $data;
    }
}
