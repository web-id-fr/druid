<?php

namespace Webid\Druid\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Webid\Druid\Components\ReusableComponent;
use Webid\Druid\Components\TextComponent;
use Webid\Druid\Components\TextImageComponent;
use Webid\Druid\DruidServiceProvider;
use Webid\Druid\Dto\LangLink;
use Webid\Druid\Enums\Langs;
use Webid\Druid\Facades\Druid;
use Webid\Druid\Models\ReusableComponent as ReusableComponentModel;
use Webid\Druid\Services\LanguageSwitcher;

class TestCase extends Orchestra
{
    use DatabaseTransactions;

    private ?Collection $languageSwitcherLinks = null;

    protected function defineEnvironment($app): void
    {
        View::addLocation(Druid::package_base_path('resources/views'));
        $app->instance('path.public', Druid::package_base_path());

        if (Druid::isMultilingualEnabled()) {
            View::share('languageSwitcher', $this->getLanguageSwitcher());
            View::share('currentLocale', Druid::getCurrentLocale());
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('app.key', 'base64:'.base64_encode(random_bytes(32)));

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Webid\\Druid\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            DruidServiceProvider::class,
            ImageServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        $app['config']->set('cms.blog.posts_per_page', 10);
        $app['config']->set('cms.blog.prefix', 'blog');
        $app['config']->set('cms.enable_multilingual_feature', true);
        $app['config']->set('cms.enable_default_blog_routes', true);
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
        ]);
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
        $app['config']->set('curator.accepted_file_types', [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/svg+xml',
            'application/pdf',
        ]);
        $app['config']->set('curator.cloud_disks', [
            's3',
            'cloudinary',
            'imgix',
        ]);

        $migration = include __DIR__.'/../database/migrations/create_druid_table.php.stub';
        $migration->up();
    }

    /**
     * @return Collection<LangLink>
     */
    private function getLanguageSwitcher(): Collection
    {
        if ($this->languageSwitcherLinks) {
            return $this->languageSwitcherLinks;
        }

        /** @var LanguageSwitcher $languageSwitcher */
        $languageSwitcher = app()->make(LanguageSwitcher::class);

        $this->languageSwitcherLinks = $languageSwitcher->getLinks();

        return $this->languageSwitcherLinks;
    }
}
