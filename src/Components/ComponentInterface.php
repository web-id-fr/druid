<?php

namespace Webid\Druid\Components;

use Illuminate\Contracts\View\View;

interface ComponentInterface
{
    /**
     * @return array<int, string>
     */
    public static function blockSchema(): array;

    public static function fieldName(): string;

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toBlade(array $data): View;

    /**
     * @param  array<string, mixed>  $data
     */
    public static function toSearchableContent(array $data): string;
}
