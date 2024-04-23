<?php

namespace Webid\Druid\Enums;

use Filament\Support\Contracts\HasLabel;

enum PostStatus: string implements HasLabel
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::PUBLISHED => __('Published'),
            self::ARCHIVED => __('Archived'),
        };
    }
}
