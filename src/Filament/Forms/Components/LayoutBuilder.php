<?php

namespace Outerweb\FilamentLayoutBuilder\Filament\Forms\Components;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class LayoutBuilder extends Builder
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->configureBlocks()
            ->addActionLabel(__('filament-layout-builder::translations.actions.add_block'))
            ->reorderableWithButtons()
            ->collapsible()
            ->collapsed()
            ->cloneable()
            ->cloneAction(function (Action $action) {
                return $action->action(function (array $arguments, Builder $component): void {
                    $newUuid = $component->generateUuid();
    
                    $items = $component->getState();

                    $newItem = $items[$arguments['item']];
                    $newItem['data']['layout-builder-block']['id'] = Str::uuid()->toString();
    
                    if ($newUuid) {
                        $items[$newUuid] = $newItem;
                    } else {
                        $items[] = $newItem;
                    }
    
                    $component->state($items);
    
                    $component->collapsed(false, shouldMakeComponentCollapsible: false);
    
                    $component->callAfterStateUpdated();
                });
            })
            ->blockNumbers(false)
            ->mutateDehydratedStateUsing(static function (?array $state) : array {
                return collect(array_values($state) ?? [])
                    ->filter(function ($block) {
                        return isset ($block['data']['layout-builder-block']['id']);
                    })
                    ->toArray();
            });
    }

    protected function configureBlocks() : self
    {
        $blocks = $this->getUserDefinedBlocks()
            ->map(function (string $blockClassName) {
                $class = new $blockClassName();

                return Builder\Block::make($class::class)
                    ->label($class->label())
                    ->icon($class->icon())
                    ->maxItems($class->maxItems())
                    ->schema([
                        Hidden::make('layout-builder-block.id')
                            ->default(Str::uuid()->toString()),
                        ...$class->schema(),
                    ]);
            })
            ->toArray();

        return $this->blocks($blocks);
    }

    protected function getUserDefinedBlocks() : Collection
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

    public function getState() : mixed
    {
        return parent::getState();
    }
}
