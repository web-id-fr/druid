<?php

namespace Webid\Druid\Filament\Resources\PostResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Webid\Druid\Filament\Resources\PostResource;
use Webid\Druid\Http\Resources\MediaResource;
use Webid\Druid\Repositories\MediaRepository;
use Webid\Druid\Services\ComponentDisplayContentExtractor;

class EditPost extends EditRecord
{
    use HasPreviewModal;

    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            PreviewAction::make(),
        ];
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
