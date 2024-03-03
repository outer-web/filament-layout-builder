<?php

namespace Outerweb\FilamentLayoutBuilder\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Container extends Component
{
    public function __construct(public ?array $blocks)
    {
        $this->configureBlocks();
    }

    public function render(): View|Closure|string
    {
        return view('filament-layout-builder::components.container');
    }

    protected function configureBlocks(): void
    {
        $this->blocks = collect($this->blocks)
            ->map(function (mixed $block, int $index) {
                if ($block instanceof Block) {
                    return $block;
                }

                if (is_string($block)) {
                    $block = json_decode($block, true);
                }

                if (is_array($block)) {
                    $componentName = $block['type'];
                    $component = "\\App\\View\\Components\\LayoutBuilder\\{$componentName}";

                    if (!class_exists($component)) {
                        return null;
                    }

                    $typeIndex = collect($this->blocks)->where('type', $componentName)->count() - 1;

                    return new $component(
                        data: $block['data'],
                        type: $componentName,
                        index: $index,
                        typeIndex: $typeIndex,
                    );
                }

                return null;
            })
            ->all();
    }
}
