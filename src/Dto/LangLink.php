<?php

namespace Webid\Druid\Dto;

class LangLink
{
    private function __construct(
        readonly public string $url,
        readonly public string $lang,
    ) {}

    public static function make(string $url, string $lang): self
    {
        return new self(
            $url,
            $lang,
        );
    }
}
