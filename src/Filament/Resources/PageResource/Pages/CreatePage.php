<?php

namespace Webid\Druid\Filament\Resources\PageResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Filament\Resources\PageResource;
use Webid\Druid\Models\Page;
use Webid\Druid\Services\ComponentDisplayContentExtractor;

class CreatePage extends CreateRecord
{
    use HasPreviewModal;

    protected static string $resource = PageResource::class;

    protected function afterCreate(): void
    {
        /** @var Page $page */
        $page = $this->record;

        if (Druid::isMultilingualEnabled()) {
            if ($page->lang === Druid::getDefaultLocale()) {
                $page->update(['translation_origin_model_id' => $page->getKey()]);
            }

            if ($page->translation_origin_model_id === null) {
                $page->update(['translation_origin_model_id' => $page->getKey()]);
            }
        }

        $page->save();
    }

    protected function getActions(): array
    {
        return [
            PreviewAction::make(),
        ];
    }

    protected function getPreviewModalView(): ?string
    {
        return 'preview.page';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'page';
    }

    protected function mutatePreviewModalData(array $data): array
    {
        /** @var ComponentDisplayContentExtractor $componentDisplayContentExtractor */
        $componentDisplayContentExtractor = app()->make(ComponentDisplayContentExtractor::class);

        $data['content'] = $componentDisplayContentExtractor->getContentFromBlocks($data['page']['content']);

        return $data;
    }
}
