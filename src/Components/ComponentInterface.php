<?php

namespace Webid\Druid\Components;

interface ComponentInterface
{
    function blockSchema(): array;

    function fieldName(): string;
}
