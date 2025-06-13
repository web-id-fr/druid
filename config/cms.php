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
     |
     | You can override default Dru^ID models with custom models.
     | /!\ The overridden models must extend existing ones.
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
     |
     | 'enable_menu_module': (bool) Enable/Disable navigation menu model
     | 'menu.menu_items_relation_manager': (RelationManager) The navigation menu Filament fields configurator.
     |  It can be overridden as long as it extends the default Filament RelationManager
     | 'menu.loaded_menus': (string) The navigation menu keys to automatically load in the pages context (for Blade usage only so far)
     |--------------------------------------------------------------------------
     */

    'enable_menu_module' => true,
    'menu' => [
        'menu_items_relation_manager' => \Webid\Druid\Filament\Resources\MenuResource\RelationManagers\ItemsRelationManager::class,
        'loaded_menus' => [
            'main-menu',
            'footer-menu',
        ],
    ],

    /*
     |--------------------------------------------------------------------------
     | Page
     |
     | 'enable_page_module': (bool) Enable/Disable pages model
     | 'enable_default_page_routes': (bool) Enable/Disable default slug based routing system
     | 'front_page_id': (int|null) Set a given page ID as the front page
     |--------------------------------------------------------------------------
     */

    'enable_page_module' => true,
    'enable_default_page_routes' => true,
    'front_page_id' => null,

    /*
     |--------------------------------------------------------------------------
     | Multilingual feature
     |
     | 'enable_multilingual_feature': (bool) Enable/Disable the multilingual feature
     | 'locales': (array) Defines the application available languages
     | 'default_locale': (string) The default language locale key
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
     |
     | 'disable_robots_follow': (bool) The default robot follow meta value. Can be overridden on each content
     |--------------------------------------------------------------------------
     */
    'disable_robots_follow' => env('DISABLE_ROBOTS_FOLLOW', false),

    /*
     |--------------------------------------------------------------------------
     | Components
     |
     | The blocks that will be used in the page builder. You can easily enable custom components by adding them in this array
     | You can adjust which model (between Page, Post and ReusableComponent) can use each component with the 'disabled_for' option
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
     |
     | 'type': (string) The class used to render contents to the front
     |
     | The default 'Webid\Druid\Services\ContentRenderer\BladeRenderer' class returns a Blade view
     | The 'Webid\Druid\Services\ContentRenderer\ApiRenderer' alternative will return json resources instead
     |
     | You can use a custom renderer as long as it implements the 'ContentRenderer' interface.
     |
     |--------------------------------------------------------------------------
     */
    'content_renderer' => [
        'type' => BladeRenderer::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Blog
     |
     | 'enable_blog_module': (bool) Enable/Disable articles model
     | 'enable_default_blog_routes': (bool) Enable/Disable default slug based routing system
     | 'blog.posts_per_page': (int) The number of posts to display on each blog page
     | 'blog.prefix': (string) The route prefix for each post url (eg: home_url/blog/slug-of-the-post where blog is the prefix)
     |--------------------------------------------------------------------------
     */
    'enable_blog_module' => true,
    'enable_default_blog_routes' => true,
    'blog' => [
        'posts_per_page' => 10,
        'prefix' => 'blog',
    ],
];
