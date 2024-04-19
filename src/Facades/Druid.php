<?php

namespace Webid\Druid\Facades;

use Illuminate\Support\Facades\Facade;

class Druid extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'druid';
    }
}
