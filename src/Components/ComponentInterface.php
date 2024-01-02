<?php

namespace Webid\Druid\Components;

interface ComponentInterface
{
    public static function blockSchema(): array;

    public static function fieldName(): string;

    static function toBlade(array $data): string;
}
