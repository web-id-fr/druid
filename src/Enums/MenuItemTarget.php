<?php

namespace Webid\Druid\Enums;

use Filament\Support\Contracts\HasLabel;

enum MenuItemTarget: string implements HasLabel
{
    case SELF = 'self';
    case BLANK = 'blank';

    public function getLabel(): string
    {
        return match ($this) {
            self::SELF => __('Same window'),
            self::BLANK => __('New window'),
        };
    }

    public function getHtmlProperty(): string
    {
        return match ($this) {
            self::SELF => '_self',
            self::BLANK => '_blank',
        };
    }
}
