<?php

declare(strict_types=1);

namespace Outerweb\FilamentLayoutBuilder\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Outerweb\FilamentLayoutBuilder\Facades\LayoutBuilder;

class Container extends Component
{
    protected string $view = 'filament-layout-builder::components.container';

    /**
     * @param  iterable<int, Block>  $blocks
     */
    public function __construct(public iterable $blocks)
    {
        $this->blocks = Collection::wrap($blocks)
            ->map(function (Block|array $block): Block {
                if ($block instanceof Block) {
                    return $block;
                }

                /** @var class-string<Block> $blockClass */
                $blockClass = LayoutBuilder::getDefinedBlockClass($block['type']);

                return $blockClass::make(
                    data: $block['data']['data'] ?? [],
                    meta: $block['data']['meta'] ?? [],
                );
            });
    }

    public function render(): View|Closure|string
    {
        return view($this->view);
    }
}
