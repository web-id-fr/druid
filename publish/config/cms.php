<?php

declare(strict_types=1);

use Webid\Druid\Models\ReusableComponent;

return [
    /*
     |--------------------------------------------------------------------------
     | Multilingual feature
     |--------------------------------------------------------------------------
     */
    'enable_multilingual_feature' => false,
    'locales' => [
        \Webid\Druid\Enums\Langs::EN->value => [
            'label' => 'English',
        ],
        \Webid\Druid\Enums\Langs::FR->value => [
            'label' => 'Français',
        ],
        \Webid\Druid\Enums\Langs::DE->value => [
            'label' => 'German',
        ],
    ],
    'default_locale' => \Webid\Druid\Enums\Langs::EN->value,

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
            'class' => \Webid\Druid\Components\TextComponent::class,
        ],
        [
            'class' => \Webid\Druid\Components\TextImageComponent::class,
        ],
        [
            'class' => \Webid\Druid\Components\ReusableComponent::class,
            'disabled_for' => [
                ReusableComponent::class,
            ],
        ],
    ],

    /*
     |--------------------------------------------------------------------------
     | Views
     |--------------------------------------------------------------------------
     */
    'views' => [
        'type' => \Webid\Druid\Enums\RenderType::BLADE->value,
    ],

    /*
     |--------------------------------------------------------------------------
     | Blog
     |--------------------------------------------------------------------------
     */
    'blog' => [
        'prefix' => 'blog',
    ],
];
