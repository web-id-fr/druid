# Druid

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
* Laravel >= 10
* Composer 2
* MariaDB / MySQL
* Laravel Filament 3.x
* Filament Curator

## Installation

In order to install Dru^ID CMS, you first need to have a Laravel Filament running installation with the Filament Curator admin.

Please follow the installation process

- For Filament here: https://filamentphp.com/docs/3.x/panels/installation
- For Curator here: https://github.com/awcodes/filament-curator

```
composer require webid/druid:"^0.1"
```

```
php artisan vendor:publish --provider="Webid\Druid\DruidServiceProvider"
```

```
php artisan migrate
```

Read on customize the `config/cms.php` file specially if you need to enable the multilingual feature.
It's better to choose the default language before writing content.

## The admin panel

Dru^ID has been built on top of the Filament package which means than by default, you'll find the administration panel to hte `/admin` route.

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

## Helpers

### Multilingual helpers

| Function                              | Description                                                                        |
|---------------------------------------|------------------------------------------------------------------------------------|
| `isMultilingualEnabled(): bool`       | Returns `true` if `enable_multilingual_feature` is set to true in `config/cms.php` |
| `getDefaultLocale() : Langs`          | Return the default `Lang` Enum set in `config/cms.php`                             |
| `getDefaultLocaleKey() : string`      | Same as previous but returns the local key                                         |
| `getLocales() : array`                | Returns an array of locale data defined in `config/cms.php`                        |
| `getCurrentLocale() : Lang`           | Returns the current Lang chosen by the visitor                                     |
| `getLanaguageSwitcher() : Collection` | Returns a Collection of links in different languages to switch to                  |

### Navigation menus helpers

| Function                                                           | Description                                            |
|--------------------------------------------------------------------|--------------------------------------------------------|
| `getNavigationMenuBySlug(string $slug): Menu`                      | Returns a `Menu` DTO with all the nested links details |
| `getNavigationMenuBySlugAndLang(string $slug, Langs $lang) : Menu` | Same as preview but with a given language              |

### Settings helpers

| Function                              | Description                                                     |
|---------------------------------------|-----------------------------------------------------------------|
| `getSettingByKey(string $key): mixed` | Returns the value of a setting defined in the admin panel       |
| `getSettings(): Collection`           | Returns a collection of all settings defined in the admin panel |
|  `isSettingsPageEnable(): bool`       | Returns `true` if the settings page is enabled                  |
| `settingsPage(): SettingsInterface`   | Returns the settings page class used to build the form          |

## Services

### NavigationMenuManager

When you have configured your navigation menus in the admin panel, you can have access to it using
the `Webid\Druid\Services\NavigationMenuManager\NavigationMenuManager` class
as a dependency or using the navigation menu helpers described in the `Helpers` section.

Once you have your menu manager instance, you can request a menu by slug and lang using the following method.

`$mainMenu = $menuManager->getBySlug('main');`

If you use the multilingual feature, you can have the same menu `slug` for several language
so you can use the `getCurrentLocale()` helper function to dynamize the method call.

`$mainMenuInCurrentLanguage = $menuManager->getBySlugAndLang('main', getCurrentLocale());`

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


