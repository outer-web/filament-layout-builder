<?php

namespace Outerweb\FilamentLayoutBuilder\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class Block extends Component
{
    protected string $view = 'filament-layout-builder::components.block';

    protected ?string $label = null;

    protected ?string $icon = null;

    protected ?int $maxItems = null;

    public function __construct(
        public ?array $data = [],
        public ?string $type = null,
        public ?int $index = null,
        public ?int $typeIndex = null,
    ) {
        //
    }

    public function schema(): array
    {
        return [];
    }

    public function label(): string
    {
        return $this->label ?? Str::headline(basename(static::class));
    }

    public function icon(): ?string
    {
        return $this->icon;
    }

    public function maxItems(): ?int
    {
        return $this->maxItems;
    }

    public function formatData(array $data): array
    {
        return $data;
    }

    public function render(): View|Closure|string
    {
        $layoutBlockData = $this->formatLayoutBlockData();

        $this->withAttributes([
            'id' => 'layout-builder-block-' . $layoutBlockData->id,
            'data-block-id' => $layoutBlockData->id,
            'data-block-type' => Str::slug($layoutBlockData->type),
            'data-block-index' => $layoutBlockData->index,
            'data-block-type-index' => $layoutBlockData->type_index,
        ]);

        return view($this->view, [
            ...$this->formatData($this->data),
            'block' => $layoutBlockData,
            'attributes' => $this->attributes,
        ]);
    }

    public function formatLayoutBlockData(): object
    {
        $blockData = $this->data['layout-builder-block'] ?? [];

        return (object) [
            'id' => $blockData['id'] ?? Str::uuid()->toString(),
            'type' => $this->type,
            'index' => $this->index,
            'type_index' => $this->typeIndex,
        ];
    }
}
