<?php

namespace Webid\Druid\Dto;

class Lang
{
    private function __construct(
        readonly public string $key,
        readonly public string $label,
    ) {}

    public static function make(string $key, string $label): self
    {
        return new self(
            $key,
            $label,
        );
    }
}
