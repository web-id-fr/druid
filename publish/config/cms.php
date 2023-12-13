<?php

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
            'enable_for_pages' => true,
            'enable_for_posts' => true,
        ],
        [
            'class' => \Webid\Druid\Components\TextImageComponent::class,
            'enable_for_pages' => true,
            'enable_for_posts' => true,
        ],
    ],
];
