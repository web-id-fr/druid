<?php

namespace Webid\Druid\Providers;

use Illuminate\Support\ServiceProvider;
use Webid\Druid\Facades\Druid;

class TestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (! $this->app->environment('testing')) {
            return;
        }

        $this->loadMigrationsFrom(Druid::packageBasePath('vendor/orchestra/testbench-core/laravel/migrations'));
        $this->loadMigrationsFrom(Druid::packageBasePath('tests/Database/Migrations'));
    }
}
