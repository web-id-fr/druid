<?php

declare(strict_types=1);

use Awcodes\Curator\Models\Media;
use Webid\Druid\Components\HintComponent;
use Webid\Druid\Components\ImageComponent;
use Webid\Druid\Components\ReusableComponent;
use Webid\Druid\Components\TextComponent;
use Webid\Druid\Components\TextImageComponent;
use Webid\Druid\Models\Category;
use Webid\Druid\Models\Menu;
use Webid\Druid\Models\MenuItem;
use Webid\Druid\Models\Page;
use Webid\Druid\Models\Post;
use Webid\Druid\Models\ReusableComponent as ReusableComponentModel;
use Webid\Druid\Services\ContentRenderer\BladeRenderer;

return [
    /*
     |--------------------------------------------------------------------------
     | Models
     |--------------------------------------------------------------------------
     */

    'models' => [
        'user' => \App\Models\User::class,
        'media' => Media::class,
        'page' => Page::class,
        'post' => Post::class,
        'category' => Category::class,
        'menu' => Menu::class,
        'menu_item' => MenuItem::class,
        'reusable_component' => ReusableComponentModel::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Menu
     |--------------------------------------------------------------------------
     */

    'enable_menu_module' => true,
    'menu' => [
        'menu_items_relation_manager' => \Webid\Druid\Filament\Resources\MenuResource\RelationManagers\ItemsRelationManager::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Page
     |--------------------------------------------------------------------------
     */

    'enable_page_module' => true,

    /*
     |--------------------------------------------------------------------------
     | Multilingual feature
     |--------------------------------------------------------------------------
     */
    'enable_multilingual_feature' => false,
    'locales' => [
        'en' => [
            'label' => 'English',
        ],
        'fr' => [
            'label' => 'French',
        ],
        'de' => [
            'label' => 'German',
        ],
    ],
    'default_locale' => 'en',

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
            'class' => ImageComponent::class,
        ],
        [
            'class' => TextImageComponent::class,
        ],
        [
            'class' => HintComponent::class,
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
     | Contents renderer
     |--------------------------------------------------------------------------
     */
    'content_renderer' => [
        'type' => BladeRenderer::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Blog
     |--------------------------------------------------------------------------
     */
    'enable_blog_module' => true,
    'enable_default_blog_routes' => true,
    'blog' => [
        'posts_per_page' => 10,
        'prefix' => 'blog',
    ],
];
