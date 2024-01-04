<?php

namespace Webid\Druid\Components;

use Illuminate\View\View;

interface ComponentInterface
{
    public static function blockSchema(): array;

    public static function fieldName(): string;

    static function toBlade(array $data): View;
}
