<?php

namespace Outerweb\FilamentLayoutBuilder\Filament\Forms\Components;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LayoutBuilder extends Builder
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->configureBlocks()
            ->addActionLabel(__('filament-layout-builder::translations.actions.add_block'))
            ->reorderableWithButtons()
            ->collapsible()
            ->collapsed()
            ->cloneable();
    }

    protected function configureBlocks(): self
    {
        $blocks = $this->getUserDefinedBlocks()
            ->map(function (string $blockClassName) {
                $class = new $blockClassName();

                return Builder\Block::make($class->label())
                    ->icon($class->icon())
                    ->maxItems($class->maxItems())
                    ->schema([
                        Hidden::make('layout-builder-block.id')
                            ->required(),
                        ...$class->schema()
                    ]);
            })
            ->toArray();

        return $this->blocks($blocks);
    }

    protected function getUserDefinedBlocks(): Collection
    {
        $classes = collect(scandir(app_path('View/Components/LayoutBuilder')))
            ->filter(function (string $file) {
                return str_ends_with($file, '.php');
            })
            ->map(function (string $file) {
                return 'App\\View\\Components\\LayoutBuilder\\' . str_replace('.php', '', $file);
            })
            ->filter(function (string $class) {
                return class_exists($class);
            });

        return $classes;
    }

    public function getState(): mixed
    {
        $state = parent::getState();

        return collect($state)
            ->map(function ($block) {
                if (!isset ($block['data']['layout-builder-block']['id']) || is_null($block['data']['layout-builder-block']['id'])) {
                    $block['data']['layout-builder-block']['id'] = Str::uuid()->toString();
                }

                return $block;
            })
            ->toArray();
    }
}
