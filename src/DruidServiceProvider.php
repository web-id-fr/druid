<?php

namespace Webid\Druid;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Webid\Druid\Console\Commands\DemoSeeder;
use Webid\Druid\Http\Middleware\MultilingualFeatureForbidden;
use Webid\Druid\Http\Middleware\MultilingualFeatureRequired;
use Webid\Druid\Services\Admin\FilamentFieldsBuilders\FilamentPageFieldsBuilder;
use Webid\Druid\Services\Admin\FilamentFieldsBuilders\FilamentPostFieldsBuilder;
use Webid\Druid\Services\Admin\FilamentFieldsBuilders\FilamentSettingsFieldsBuilder;
use Webid\Druid\Services\DefaultFilamentFieldsProvider;

class DruidServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('druid')
            ->hasConfigFile('cms')
            ->hasViews()
            ->hasRoute('routes')
            ->hasCommands(DemoSeeder::class)
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
        $this->registerAdminFieldsBuilders();
    }

    protected function registerDruid(): self
    {
        $this->app->singleton(Druid::class, function () {
            return new Druid();
        });

        $this->app->alias(Druid::class, 'druid');

        return $this;
    }

    protected function registerAdminFieldsBuilders(): self
    {
        /** @var DefaultFilamentFieldsProvider $defaultFieldsProvider */
        $defaultFieldsProvider = $this->app->make(DefaultFilamentFieldsProvider::class);

        $this->app->singleton(FilamentSettingsFieldsBuilder::class, function () use ($defaultFieldsProvider) {
            $builder = new FilamentSettingsFieldsBuilder();
            $builder->updateFields($defaultFieldsProvider->getDefaultSettingsFields());

            return $builder;
        });

        $this->app->singleton(FilamentPageFieldsBuilder::class, function () use ($defaultFieldsProvider) {
            $builder = new FilamentPageFieldsBuilder();
            $builder->updateFields($defaultFieldsProvider->getDefaultPagesFields());

            return $builder;
        });

        $this->app->singleton(FilamentPostFieldsBuilder::class, function () use ($defaultFieldsProvider) {
            $builder = new FilamentPostFieldsBuilder();
            $builder->updateFields($defaultFieldsProvider->getDefaultPostsFields());

            return $builder;
        });

        return $this;
    }
}
