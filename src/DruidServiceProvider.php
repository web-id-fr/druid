<?php

namespace Webid\Druid;

use Illuminate\Support\ServiceProvider;

class DruidServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishFiles();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
    }

    public function register(): void
    {
    }

    protected function publishFiles(): void
    {
        $this->publishes([
            __DIR__ . '/../publish/app/Models/Page.php' => base_path('/app/Models/Page.php'),
        ], 'page-model');

        $this->publishes([
            __DIR__ . '/../publish/app/Http/Controllers/PageController.php' => base_path('/app/Http/Controllers/PageController.php'),
        ], 'page-controller');

        $this->publishes([
            __DIR__ . '/../publish/config/cms.php' => base_path('/config/cms.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../publish/app/Filament' => base_path('/app/Filament'),
        ], 'filament-resources');
    }
}
