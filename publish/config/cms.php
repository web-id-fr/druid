<?php

declare(strict_types=1);

use Webid\Druid\App\Components\ReusableComponent;
use Webid\Druid\App\Components\TextComponent;
use Webid\Druid\App\Components\TextImageComponent;
use Webid\Druid\App\Enums\Langs;
use Webid\Druid\App\Enums\RenderType;
use Webid\Druid\App\Models\ReusableComponent as ReusableComponentModel;

return [
    /*
     |--------------------------------------------------------------------------
     | Multilingual feature
     |--------------------------------------------------------------------------
     */
    'enable_multilingual_feature' => false,
    'locales' => [
        Langs::EN->value => [
            'label' => 'English',
        ],
        Langs::FR->value => [
            'label' => 'Français',
        ],
        Langs::DE->value => [
            'label' => 'German',
        ],
    ],
    'default_locale' => Langs::EN->value,

    /*
     |--------------------------------------------------------------------------
     | SEO
     |--------------------------------------------------------------------------
     */
    'disable_robots_follow' => env('DISABLE_ROBOTS_FOLLOW', false),

    /*
     |--------------------------------------------------------------------------
     | Components
     |--------------------------------------------------------------------------
     */
    'components' => [
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
    ],

    /*
     |--------------------------------------------------------------------------
     | Views
     |--------------------------------------------------------------------------
     */
    'views' => [
        'type' => RenderType::BLADE->value,
    ],

    /*
     |--------------------------------------------------------------------------
     | Blog
     |--------------------------------------------------------------------------
     */
    'enable_default_blog_routes' => true,
    'blog' => [
        'posts_per_page' => 10,
        'prefix' => 'blog',
    ],
];
