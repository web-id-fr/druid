<?php

namespace Webid\Druid\Components;

use Filament\Forms\Components\RichEditor;
use Illuminate\Contracts\View\View;
use Webmozart\Assert\Assert;

class TextComponent implements ComponentInterface
{
    public static function blockSchema(): array
    {
        return [
            RichEditor::make('content')
                ->label(__('Content'))
                ->required(),
        ];
    }

    public static function fieldName(): string
    {
        return 'text';
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toBlade(array $data): View
    {
        return view('druid::components.text', [
            'content' => $data['content'],
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toSearchableContent(array $data): string
    {
        $content = $data['content'] ?? '';
        Assert::string($content);

        return strip_tags($content);
    }

    public static function imagePreview(): string
    {
        return '/vendor/druid/cms/images/components/text_component.png';
    }
}
