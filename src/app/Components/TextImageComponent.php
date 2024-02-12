<?php

namespace Webid\Druid\App\Components;

use Awcodes\Curator\Components\Forms\CuratorPicker;
use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\View\View;
use Webid\Druid\App\Repositories\MediaRepository;

class TextImageComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            RichEditor::make('content')
                ->label(__('Content'))
                ->required(),
            // @phpstan-ignore-next-line
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
     * @param array<string, mixed> $data
     */
    public static function toBlade(array $data): View
    {
        $mediaRepository = app(MediaRepository::class);
        return view('druid::components.text-image', [
            'content' => $data['content'],
            'image' => $mediaRepository->findById($data['image']),
        ]);
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function toSearchableContent(array $data): string
    {
        return '';
    }
}
