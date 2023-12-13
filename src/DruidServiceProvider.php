<?php

namespace Webid\Druid;

use Illuminate\Support\ServiceProvider;

class DruidServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishModels();
        $this->publishFilamentResources();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register(): void
    {
    }

    protected function publishModels(): void
    {
        $this->publishes([
            __DIR__ . '/../publish/app/Models/Page.php' => base_path('/app/Models/Page.php'),
        ], 'page-model');
    }

    protected function publishFilamentResources(): void
    {
        $this->publishes([
            __DIR__ . '/../publish/app/Filament' => base_path('/app/Filament'),
        ], 'filament-resources');
    }
}
