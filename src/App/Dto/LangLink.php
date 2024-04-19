<?php

namespace Webid\Druid\App\Dto;

use Webid\Druid\App\Enums\Langs;

class LangLink
{
    private function __construct(
        readonly public string $url,
        readonly public Langs $lang,
    ) {
    }

    public static function make(string $url, Langs $lang): self
    {
        return new self(
            $url,
            $lang,
        );
    }
}
