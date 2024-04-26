<?php

namespace Webid\Druid\Components;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Contracts\View\View;
use Webid\Druid\Http\Resources\MediaResource;
use Webid\Druid\Repositories\MediaRepository;

class ImageComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            CuratorPicker::make('image')
                ->label(__('Image'))
                ->preserveFilenames()
                ->required(),
        ];
    }

    public static function fieldName(): string
    {
        return 'image';
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

        return view('druid::components.image', [
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
}
