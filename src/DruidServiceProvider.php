<?php

namespace Webid\Druid;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Webid\Druid\App\Http\Middleware\MultilingualFeatureForbidden;
use Webid\Druid\App\Http\Middleware\MultilingualFeatureRequired;

class DruidServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('druid')
            ->hasConfigFile('cms')
            ->hasRoute('routes')
            ->hasMigrations([
                '2020_02_03_133458_create_pages_table',
                '2023_12_13_144213_create_reusable_components_table',
                '2023_12_14_083142_create_posts_table',
                '2023_12_14_083148_create_categories_table',
                '2023_12_14_083213_create_category_post_table',
                '2023_12_14_084030_create_post_user_table',
                '2023_12_15_092405_create_menus_table',
                '2024_01_02_142233_create_menu_items_table',
                '2024_02_20_142233_create_settings_table',
            ])
            ->hasViews();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Druid::class);
        $this->app->alias(Druid::class, 'druid');
    }

    public function registeringPackage(): void
    {
        app('router')->aliasMiddleware('multilingual-required', MultilingualFeatureRequired::class);
        app('router')->aliasMiddleware('multilingual-forbidden', MultilingualFeatureForbidden::class);
    }
}
