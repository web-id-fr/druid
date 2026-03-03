<?php

namespace Webid\Druid\Dto;

class Lang
{
    private function __construct(
        public readonly string $key,
        public readonly string $label,
    ) {}

    public static function make(string $key, string $label): self
    {
        return new self(
            $key,
            $label,
        );
    }
}
