<?php

namespace Webid\Druid\Filament\Resources\PostResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Filament\Resources\PostResource;
use Webid\Druid\Http\Resources\MediaResource;
use Webid\Druid\Models\Post;
use Webid\Druid\Repositories\MediaRepository;
use Webid\Druid\Services\ComponentDisplayContentExtractor;

class CreatePost extends CreateRecord
{
    use HasPreviewModal;

    protected static string $resource = PostResource::class;

    protected function afterCreate(): void
    {
        /** @var Post $post */
        $post = $this->record;

        if (Druid::isMultilingualEnabled()) {
            if ($post->lang === Druid::getDefaultLocale()) {
                $post->update(['translation_origin_model_id' => $post->getKey()]);
            }
        }

        $post->save();
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
