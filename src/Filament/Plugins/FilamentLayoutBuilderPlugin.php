<?php

namespace Outerweb\FilamentLayoutBuilder\Filament\Plugins;

use Filament\Contracts\Plugin;
use Filament\Panel;

class FilamentLayoutBuilderPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'outerweb-filament-layout-builder';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
