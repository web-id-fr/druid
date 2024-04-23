<?php

namespace Webid\Druid;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Webid\Druid\Http\Middleware\MultilingualFeatureForbidden;
use Webid\Druid\Http\Middleware\MultilingualFeatureRequired;

class DruidServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('druid')
            ->hasConfigFile('cms')
            ->hasViews()
            ->hasRoute('routes')
            ->hasMigrations([
                '01_create_pages_table',
                '02_create_reusable_components_table',
                '03_create_posts_table',
                '04_create_categories_table',
                '05_create_category_post_table',
                '06_create_post_user_table',
                '07_create_menus_table',
                '08_create_menu_items_table',
                '09_create_settings_table',
            ])
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishAssets()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('web-id-fr/druid');
            });
    }

    public function registeringPackage(): void
    {
        app('router')->aliasMiddleware('multilingual-required', MultilingualFeatureRequired::class);
        app('router')->aliasMiddleware('multilingual-forbidden', MultilingualFeatureForbidden::class);
    }

    public function packageBooted(): void
    {
        $this->registerDruid();
    }

    protected function registerDruid(): self
    {
        $this->app->singleton(Druid::class, function () {
            return new Druid();
        });

        $this->app->alias(Druid::class, 'druid');

        return $this;
    }
}
