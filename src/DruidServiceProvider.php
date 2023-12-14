<?php

namespace Webid\Druid;

use Illuminate\Support\ServiceProvider;

class DruidServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishFiles();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register(): void
    {
    }

    protected function publishFiles(): void
    {
        $this->publishes([
            __DIR__ . '/../publish/app/Models/Post.php' => base_path('/app/Models/Post.php'),
            __DIR__ . '/../publish/app/Models/Page.php' => base_path('/app/Models/Page.php'),
        ], 'models');

        $this->publishes([
            __DIR__ . '/../publish/config/cms.php' => base_path('/config/cms.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../publish/app/Filament' => base_path('/app/Filament'),
        ], 'filament-resources');
    }
}
