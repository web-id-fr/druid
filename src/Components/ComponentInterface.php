<?php

namespace Webid\Druid\Components;

interface ComponentInterface
{
    static function blockSchema(): array;

    static function fieldName(): string;

    static function toHtml(array $data): string;
}
