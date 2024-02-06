<?php

namespace Webid\Druid;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Support\ServiceProvider;
use Webid\Druid\App\Http\Middleware\MultilingualFeatureForbidden;
use Webid\Druid\App\Http\Middleware\MultilingualFeatureRequired;

class DruidServiceProvider extends ServiceProvider
{
    public function boot(Router $router): void
    {
        $this->loadViewsFrom(__DIR__.'/resources/views', 'druid');
        $this->publishFiles();
        $this->loadMigrationsFrom(__DIR__.'/database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');

        ViewFacade::addLocation(__DIR__.'/resources/views/');
    }

    public function register(): void
    {
        app('router')->aliasMiddleware('multilingual-required', MultilingualFeatureRequired::class);
        app('router')->aliasMiddleware('multilingual-forbidden', MultilingualFeatureForbidden::class);
    }

    public function map(): void
    {
        Route::middleware('web')
            ->group(__DIR__.'/routes/routes.php');
    }

    protected function publishFiles(): void
    {
        $this->publishes([
            __DIR__.'/../publish/config/cms.php' => base_path('/config/cms.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../publish/app/Filament' => base_path('/app/Filament'),
        ], 'filament-resources');
    }
}
