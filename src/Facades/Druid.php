<?php

namespace Webid\Druid\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Webid\Druid\Druid
 *
 * @mixin \Webid\Druid\Druid
 */
class Druid extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Webid\Druid\Druid::class;
    }
}
