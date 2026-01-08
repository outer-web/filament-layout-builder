<?php

declare(strict_types=1);

namespace Outerweb\FilamentLayoutBuilder\Services;

use Illuminate\Support\Collection;
use Outerweb\FilamentLayoutBuilder\View\Components\Block;
use Spatie\StructureDiscoverer\Discover;

class LayoutBuilder
{
    /**
     * @return Collection<class-string<Block>>
     */
    public function getDefinedBlockClasses(): Collection
    {
        return collect(
            Discover::in(app_path())
                ->classes()
                ->extending(Block::class)
                ->get()
        );
    }

    public function getDefinedBlockClass(string $id): ?string
    {
        return $this->getDefinedBlockClasses()
            ->firstWhere(function (string $block) use ($id): bool {
                /** @var class-string<Block> $block */
                return $block::getId() === $id;
            });
    }
}
