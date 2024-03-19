<?php

namespace Outerweb\FilamentLayoutBuilder\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Container extends Component
{
    public function __construct(public array|string|Collection $blocks)
    {
        $this->configureBlocks();
    }

    public function render() : View|Closure|string
    {
        return view('filament-layout-builder::components.container');
    }

    protected function configureBlocks() : void
    {
        if (is_string($this->blocks)) {
            $this->blocks = json_decode($this->blocks, true);
        }

        if ($this->blocks instanceof Collection) {
            $this->blocks = $this->blocks->toArray();
        }

        if (is_null($this->blocks)) {
            $this->blocks = [];
        }

        $this->blocks = collect($this->blocks)
            ->map(function (mixed $block, int $index) {
                if ($block instanceof Block) {
                    return $block;
                }

                if (is_string($block)) {
                    $block = json_decode($block, true);
                }


                if (is_array($block)) {
                    $component = $block['type'];

                    if (! class_exists($component)) {
                        return null;
                    }

                    $typeIndex = collect($this->blocks)
                        ->take($index)
                        ->where('type', $component)->count();

                    return new $component(
                        data: $block['data'],
                        type: $component,
                        index: $index,
                        typeIndex: $typeIndex,
                    );
                }

                return null;
            })
            ->all();
    }
}
