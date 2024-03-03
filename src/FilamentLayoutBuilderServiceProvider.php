<?php

namespace Outerweb\FilamentLayoutBuilder;

use Outerweb\FilamentLayoutBuilder\View\Components;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentLayoutBuilderServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('filament-layout-builder')
            ->hasTranslations()
            ->hasViews()
            ->hasViewComponents(
                'filament-layout-builder',
                Components\Container::class,
                Components\Block::class,
            )
            ->hasInstallCommand(function (InstallCommand $command) {
                $composerFile = file_get_contents(__DIR__ . '/../composer.json');

                if ($composerFile) {
                    $githubRepo = json_decode($composerFile, true)['homepage'] ?? null;

                    if ($githubRepo) {
                        $command
                            ->askToStarRepoOnGitHub($githubRepo);
                    }
                }
            });
    }
}
