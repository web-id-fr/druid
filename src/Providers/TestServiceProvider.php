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

        $this->loadMigrationsFrom(Druid::package_base_path('vendor/orchestra/testbench-core/laravel/migrations'));
        $this->loadMigrationsFrom(Druid::package_base_path('tests/Database/Migrations'));
    }
}
