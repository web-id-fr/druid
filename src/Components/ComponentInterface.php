<?php

namespace Webid\Druid\Components;

interface ComponentInterface
{
    public static function blockSchema(): array;

    public static function fieldName(): string;

    public static function toHtml(array $data): string;
}
