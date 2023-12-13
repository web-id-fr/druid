# Druid

## Requirements

* PHP >= 8.2
* Laravel >= 10
* Composer 2
* MariaDB / MySQL

## Installation

```
composer require webid/druid:"^0.1"
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






