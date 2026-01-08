<?php

declare(strict_types=1);

namespace Outerweb\FilamentLayoutBuilder;

use Outerweb\FilamentLayoutBuilder\Commands\MakeLayoutBuilderBlockCommand;
use Outerweb\FilamentLayoutBuilder\Services\LayoutBuilder;
use Outerweb\FilamentLayoutBuilder\View\Components\Block;
use Outerweb\FilamentLayoutBuilder\View\Components\Container;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentLayoutBuilderServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-layout-builder';

    public static string $viewNamespace = 'filament-layout-builder';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands([
                MakeLayoutBuilderBlockCommand::class,
            ])
            ->hasTranslations()
            ->hasViews()
            ->hasViewComponents(
                'filament-layout-builder',
                Container::class,
                Block::class,
            );
    }

    public function boot(): void
    {
        parent::boot();

        $this->app->singleton('LayoutBuilder', function () {
            return new LayoutBuilder;
        });
    }
}
