<?php

declare(strict_types=1);

use Webid\Druid\App\Filament\Pages\SettingsPage\SettingsInterface;

if (! function_exists('isSettingsPageEnable')) {
    function isSettingsPageEnable(): bool
    {
        return config('cms.settings.enable_settings_page') == true;
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
