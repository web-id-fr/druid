<?php

namespace Webid\Druid\Dto;

class LangLink
{
    private function __construct(
        public readonly string $url,
        public readonly string $lang,
    ) {}

    public static function make(string $url, string $lang): self
    {
        return new self(
            $url,
            $lang,
        );
    }
}
