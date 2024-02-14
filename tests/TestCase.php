<?php

namespace Webid\Druid\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Webid\Druid\App\Components\ReusableComponent;
use Webid\Druid\App\Components\TextComponent;
use Webid\Druid\App\Components\TextImageComponent;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Models\ReusableComponent as ReusableComponentModel;
use Webid\Druid\App\Providers\TestServiceProvider;
use Webid\Druid\DruidServiceProvider;

class TestCase extends OrchestraTestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations(['--database' => 'mysql']);
        $this->artisan('migrate', ['--database' => 'mysql'])->run();
    }

    /**
     * @param  Application  $app
     * @return array<int, string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            TestServiceProvider::class,
            DruidServiceProvider::class,
            ImageServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app)
    {
        View::addLocation(package_base_path('src/resources/views'));
        $app->instance('path.public', package_base_path());
    }

    /**
     * @param  Application  $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('cms.blog.prefix', 'blog');
        $app['config']->set('cms.enable_multilingual_feature', false);
        $app['config']->set('cms.default_locale', Langs::EN->value);
        $app['config']->set('cms.components', [
            [
                'class' => TextComponent::class,
            ],
            [
                'class' => TextImageComponent::class,
            ],
            [
                'class' => ReusableComponent::class,
                'disabled_for' => [
                    ReusableComponentModel::class,
                ],
            ],
        ], );
        $app['config']->set('cms.locales', [
            Langs::EN->value => [
                'label' => 'English',
            ],
            Langs::FR->value => [
                'label' => 'Français',
            ],
            Langs::DE->value => [
                'label' => 'German',
            ],
        ]);
        $app['config']->set('curator.directory', 'media');
        $app['config']->set('curator.disk', env('FILAMENT_FILESYSTEM_DISK', 'public'));
    }
}
