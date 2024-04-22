<?php

namespace Webid\Druid\App\Facades;

use Illuminate\Support\Facades\Facade;

class Druid extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Webid\Druid\Druid::class;
    }
}
