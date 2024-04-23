<?php

namespace Webid\Druid\Components;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\View\View;
use Webid\Druid\Repositories\MediaRepository;

class TextImageComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            RichEditor::make('content')
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

        $image = $mediaRepository->findById($mediaId);

        return view('druid::components.text-image', [
            'content' => $data['content'],
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
