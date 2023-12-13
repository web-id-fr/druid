<?php

use Webid\Druid\Models\ReusableComponent;

return [
    /*
     |--------------------------------------------------------------------------
     | Multilingual feature
     |--------------------------------------------------------------------------
     */
    'enable_multilingual_feature' => false,
    'locales' => [
        'en' => 'English',
        'fr' => 'Français',
    ],
    'default_language' => 'en',

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
];
