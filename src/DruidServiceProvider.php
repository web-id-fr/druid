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
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');

        ViewFacade::addLocation(__DIR__.'/resources/');
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
            __DIR__.'/../publish/app/Models/Post.php' => base_path('/app/Models/Post.php'),
            __DIR__.'/../publish/app/Models/Page.php' => base_path('/app/Models/Page.php'),
            __DIR__.'/../publish/app/Models/Category.php' => base_path('/app/Models/Category.php'),
        ], 'models');

        $this->publishes([
            __DIR__.'/../publish/app/Http/Controllers/PageController.php' => base_path('/app/Http/Controllers/PageController.php'),
        ], 'page-controller');

        $this->publishes([
            __DIR__.'/../publish/config/cms.php' => base_path('/config/cms.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../publish/app/Filament' => base_path('/app/Filament'),
        ], 'filament-resources');
    }
}
