<?php

declare(strict_types=1);

namespace Outerweb\FilamentLayoutBuilder;

use Filament\Contracts\Plugin;
use Filament\Panel;

class LayoutBuilderPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getId(): string
    {
        return 'layout-builder';
    }

    public function register(Panel $panel): void {}

    public function boot(Panel $panel): void {}
}
