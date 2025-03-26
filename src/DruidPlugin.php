<?php

namespace Webid\Druid;

use Filament\Contracts\Plugin;
use Filament\Panel;

class DruidPlugin implements Plugin
{
    public static function make(): static
    {
        /** @var static */
        return app(static::class);
    }

    public function getId(): string
    {
        return 'druid';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->discoverResources(
                in: base_path('vendor/webid/druid/src/Filament/Resources'),
                for: 'Webid\\Druid\\Filament\\Resources'
            )
            ->discoverPages(
                in: base_path('vendor/webid/druid/src/Filament/Pages'),
                for: 'Webid\\Druid\\Filament\\Pages'
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
