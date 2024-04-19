<?php

namespace Webid\Druid\App\Components;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Illuminate\Contracts\View\View;
use Webid\Druid\App\Repositories\MediaRepository;

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

        $image = $mediaRepository->findById($mediaId);

        return view('druid::components.image', [
            // @phpstan-ignore-next-line
            'image' => $image->url,
            // @phpstan-ignore-next-line
            'alt' => $image->alt,
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
