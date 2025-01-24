<?php

namespace Webid\Druid\Components;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Contracts\View\View;
use Webid\Druid\Http\Resources\MediaResource;
use Webid\Druid\Repositories\MediaRepository;

class TextImageComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            TiptapEditor::make('content')
                ->label(__('Content'))
                ->required(),
            CuratorPicker::make('image')
                ->label(__('Image'))
                ->preserveFilenames()
                ->required(),
        ];
    }

    public static function fieldName(): string
    {
        return 'textImage';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toBlade(array $data): View
    {
        /** @var MediaRepository $mediaRepository */
        $mediaRepository = app(MediaRepository::class);

        /** @var int $mediaId */
        $mediaId = $data['image'];

        return view('druid::components.text-image', [
            'content' => $data['content'],
            'image' => MediaResource::make($mediaRepository->findById($mediaId)),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toSearchableContent(array $data): string
    {
        return '';
    }

    public static function imagePreview(): string
    {
        return '/vendor/druid/cms/images/components/text_image_component.png';
    }
}
