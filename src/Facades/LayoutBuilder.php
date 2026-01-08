<?php

declare(strict_types=1);

namespace Outerweb\FilamentLayoutBuilder\Facades;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Outerweb\FilamentLayoutBuilder\Services\LayoutBuilder as LayoutBuilderService;
use Outerweb\FilamentLayoutBuilder\View\Components\Block;

/**
 * @method Collection<class-string<Block>> getDefinedBlockClasses()
 * @method ?string getDefinedBlockClass(string $id)
 */
class LayoutBuilder extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LayoutBuilderService::class;
    }
}
