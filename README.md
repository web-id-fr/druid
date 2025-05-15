![img.png](img.png)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/webid/druid.svg?style=flat-square)](https://packagist.org/packages/webid/druid)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/web-id-fr/druid/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/webid/druid/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/web-id-fr/druid/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/webid/druid/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/webid/druid.svg?style=flat-square)](https://packagist.org/packages/webid/druid)

## What is Dru^ID CMS?

Dru^ID is meant to be a light Content Management System build on top of a Laravel application.
You can install it in an existing Laravel project without breaking everything or start with a fresh installation.
This is more a toolbox for developers rather than a one click install CMS with a billion themes and plugins.

Essentially out of the box you'll get a Filament based admin panel where you can edit.

- Pages
- Posts
- Navigation menus
- Reusable blocks
- Settings

You'll also find helpers and services to manage multilingual and navigation menu features in your codebase.

## Requirements

* PHP >= 8.2
* Laravel >= 11
* Composer 2
* MariaDB / MySQL
* [Laravel Filament 3.x](https://filamentphp.com/docs/3.x/panels/installation)
* [Filament Curator](https://v2.filamentphp.com/plugins/curator)

## Installation

:warning:  In order to install Dru^ID CMS, you first need to have a Laravel Filament running installation with the Filament Curator admin.

Please follow the installation process

- For Filament here: https://filamentphp.com/docs/3.x/panels/installation
- For Curator here: https://github.com/awcodes/filament-curator

```
composer require webid/druid
```

```
php artisan vendor:publish --provider="Webid\Druid\DruidServiceProvider"
```

```
php artisan migrate
```

Create a first admin

```
php artisan filament:install --panels
php artisan make:filament-user
```

https://filamentphp.com/docs/3.x/panels/installation

Open the `app/Providers/Filament/AdminPanelProvider.php` file and register Druid in plugins like that :`

```php
return $panel
        ->plugins([
                DruidPlugin::make(),
            ]);
```

Customize the `config/cms.php` file specially if you need to enable the multilingual feature.
It's better to choose the default language before writing content.

## Load the demo dataset

If you want to seed a demo dataset, simply run the following command in a fresh installation

`php artisan druid:demo`

## The admin panel

Dru^ID has been built on top of the Filament package which means than by default, you'll find the administration panel to the `/admin` route.

The [Filament documentation](https://filamentphp.com/docs/3.x/panels/installation) will help you create your first admin if you're not already
using it inside your project.

Once you're logged-in, you can create navigation menus, pages, posts, categories and reusable components.

### Pages and posts

Pages and posts contents are based on a bunch of components such as Text, Text + Image, Separator, Quote etc.
Some of them are included in the Dru^ID package itself, but you can easily create your own components using the Filament fields system.

### Reusable components

When you need to display the exact same component in several contents for example a Call to action component, you can configure it one time
and use it multiple times wherever you like afterward. This is what we call a reusable component.

### Navigation menus

You can manually group and nest your contents inside navigation menus in the admin panel. You can choose between internal content
(page and posts) and external URLs. You can nest your menu items up to 3 levels for advanced menus usage.

### Settings

You can define some settings in the admin panel that you can use in your codebase using the `Settings` helper.
It's possible to build your own settings form page using the `SettingsInterface` class. When you're done, you have to
add the class to the `config/cms.php` file `settings.settings_form`.

## Configuration

The `config/cms.php` file contains all the configuration options for the Dru^ID package.
Is it recommended to publish the configuration file and customize it to fit your needs.
It is possible to disable some modules or features if you don't need them.

## Druid Facade

### Multilingual helpers

| Function                                     | Description                                                                        |
|----------------------------------------------|------------------------------------------------------------------------------------|
| `Druid::isMultilingualEnabled(): bool`       | Returns `true` if `enable_multilingual_feature` is set to true in `config/cms.php` |
| `Druid::getDefaultLocale() : Langs`          | Return the default `Lang` Enum set in `config/cms.php`                             |
| `Druid::getDefaultLocale() : string`         | Same as previous but returns the local key                                         |
| `Druid::getLocales() : array`                | Returns an array of locale data defined in `config/cms.php`                        |
| `Druid::getCurrentLocaleKey() : Lang`        | Returns the current Lang chosen by the visitor                                     |
| `Druid::getLanaguageSwitcher() : Collection` | Returns a Collection of links in different languages to switch to                  |

### Navigation menus helpers

| Function                                                                  | Description                                            |
|---------------------------------------------------------------------------|--------------------------------------------------------|
| `Druid::getNavigationMenuBySlug(string $slug): Menu`                      | Returns a `Menu` DTO with all the nested links details |
| `Druid::getNavigationMenuBySlugAndLang(string $slug, Langs $lang) : Menu` | Same as preview but with a given language              |

### Settings helpers

| Function                                     | Description                                                     |
|----------------------------------------------|-----------------------------------------------------------------|
| `Druid::getSettingByKey(string $key): mixed` | Returns the value of a setting defined in the admin panel       |
| `Druid::getSettings(): Collection`           | Returns a collection of all settings defined in the admin panel |
| `Druid::isSettingsPageEnabled(): bool`       | Returns `true` if the settings page is enabled                  |
| `Druid::settingsPage(): SettingsInterface`   | Returns the settings page class used to build the form          |

## Services

### NavigationMenuManager

When you have configured your navigation menus in the admin panel, you can have access to it using
the `Webid\Druid\Services\NavigationMenuManager\NavigationMenuManager` class
as a dependency or using the navigation menu helpers described in the `Helpers` section.

Once you have your menu manager instance, you can request a menu by slug and lang using the following method.

`$mainMenu = $menuManager->getBySlug('main');`

If you use the multilingual feature, you can have the same menu `slug` for several language
so you can use the `getCurrentLocaleKey()` helper function to dynamize the method call.

`$mainMenuInCurrentLanguage = $menuManager->getBySlugAndLang('main', getCurrentLocaleKey());`

## Customizing menu items

You can customize the menu items to add more attributes. Here's how to do it

1. Override the `'menu_items_relation_manager' => \Webid\Druid\Filament\Resources\MenuResource\RelationManagers\ItemsRelationManager::class,` in the `config/cms.php` file.
2. Add the new fields in the `menu_items` table.

### Language switcher

When using the multilingual feature, you certainly need to display a language switcher component that helps redirecting users
to the equivalent content in another language (only if this equivalent exists).

For that you can use the `Webid\Druid\Services\LanguageSwitcher` class as a dependency or use the `getLanaguageSwitcher()`
helper described in the helpers section.

You'll get a collection of langs details with the current URL equivalent in other languages and a key to indicate the current language.

## Rendering & Templating

Dru^ID enable by default a bunch of front-end routes to help you save some time

- The pages (`Models/Page`) detail view is accessible via the URL `homepage/{the-page-slug}` or `homepage/{lang}/{the-page-slug}`
  if the multilingual feature is enabled.
- The posts (`Models/Post`) detail view is accessible via the URL `homepage/{blog-prefix}/{the-post-slug}`
  or `homepage/{blog-prefix}/{lang}/{the-post-slug}` if the multilingual feature is enabled.

By default, these view are rendered with a basic Blade template that you can override in your project `resources/views/vendor/druid` directory
You can also choose to render a `JsonResource` instead by changing the `views.type` param in the `config/cms.php` file.

## Creating new component types

1. Create a class that implements the `Webid\Druid\Components\ComponentInterface` interface.
2. Add all methods required by the interface.
3. Register your component in the `config/cms.php` config file.

You can of course create a custom package that adds one or several components and give it to the community.

## Extending default settings and Page/Post fields

Anywhere in your app (in a Service Provider, Middleware for example), you can override the default admin
behaviour in terms of form fields

The following example will show you how to add an extra settings field

```php

/** @var FilamentSettingsFieldsBuilder $fieldsBuilder */
$fieldsBuilder = $this->app->make(FilamentSettingsFieldsBuilder::class);

$fieldsBuilder->addField(
    TextInput::make('a_first_field') // A Filament field as explained in Filament documentation
        ->label(__('A first field'))
        ->required(),
    'a_first_field' // A key to help fields targeting
);

$fieldsBuilder->addField(
    TextInput::make('a_second_field')
        ->label(__('A second field'))
        ->required(),
    'a_second_field',
    'tabs.application', // Here we provide the target path structure where we want our field to show up
    // In the settings form, we have a tabs group named `tabs`. One of the tabs is named `application` 
    before: 'another_field' // We can specify a `before` or `after` param to put the new field in a specific spot
);

```

## Tips to define a default homepage

We decide to not provide a default homepage route in the package because we think that it's better to let the developer choose the way to define it.
Here is a simple way to define a default homepage route in your `routes/web.php` file.

You can use the `Settings` model to store the homepage id and retrieve it in your controller.

```php
    HomepageController.php

    public function index(): View
    {
        /** @var Settings|null $page */
        $page = Druid::getSettingByKey('homepage_id');

        if (is_null($page)) {
            abort(404);
        }

        $homepage = $this->pageRepository->findOrFail($page->value);

        if (Druid::isMultilingualEnabled()) {
            $homepage->loadMissing('translations');
        }

        return view('druid::page.page', [
            'page' => PageResource::make($homepage)->toObject(),
        ]);
    }


    routes/web.php

    Route::get('/', [HomepageController::class, 'index'])->name('homepage');
```

## Scheduled Commands

Dru^ID comes with a scheduled command that check if articles have status scheduled_published and publish them if the publication date is reached.

To enable this feature, you need to add a schedule for this command :

```php
druid:publish-scheduled-posts
```

Laravel 11 doc to add command to scheduler :
https://laravel.com/docs/11.x/scheduling#defining-schedules

## Credits

- [Web^ID Team](https://web-id.fr/fr/agence-web-lyon)
- [All Contributors](https://github.com/web-id-fr/druid/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
