<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Webid\Druid\App\Filament\Pages\SettingsPage\SettingsInterface;
use Webid\Druid\App\Repositories\SettingsRepository;

if (! function_exists('isSettingsPageEnabled')) {
    function isSettingsPageEnabled(): bool
    {
        return config('cms.settings.enable_settings_page') === true;
    }
}

if (! function_exists('settingsPage')) {
    function settingsPage(): SettingsInterface
    {
        /** @var string $className */
        $className = config('cms.settings.settings_form');

        if (! class_exists($className)) {
            throw new \RuntimeException("$className does not exist.");
        }

        if (! is_subclass_of($className, SettingsInterface::class)) {
            throw new \RuntimeException("$className needs to implement SettingsInterface.");
        }

        return new $className();
    }
}

if (! function_exists('getSettingByKey')) {
    function getSettingByKey(string $key): ?Model
    {
        $settingsRepository = app(SettingsRepository::class);

        return $settingsRepository->findSettingByKeyName($key);
    }
}

if (! function_exists('getSettings')) {
    function getSettings(): Collection
    {
        $settingsRepository = app(SettingsRepository::class);

        return $settingsRepository->all();
    }
}
