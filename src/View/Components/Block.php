<?php

declare(strict_types=1);

namespace Outerweb\FilamentLayoutBuilder\View\Components;

use BackedEnum;
use Closure;
use Filament\Schemas\Schema;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\View\Component;
use Illuminate\View\View;

class Block extends Component
{
    use EvaluatesClosures;

    protected string $view = 'filament-layout-builder::components.block';

    public function __construct(
        public array $data = [],
        public array $meta = [],
    ) {}

    public static function make(array $data = [], array $meta = []): static
    {
        return new static(
            data: $data,
            meta: $meta,
        );
    }

    public static function getId(): string
    {
        return class_basename(static::class);
    }

    public static function getLabel(): string
    {
        return (string) str(static::getId())
            ->kebab()
            ->replace('-', ' ')
            ->ucwords();
    }

    public static function getIcon(): string|BackedEnum|null
    {
        return null;
    }

    public static function getMaxItems(): ?int
    {
        return null;
    }

    public static function schema(Schema $schema): Schema
    {
        return $schema->components([

        ]);
    }

    public function dehydrateData(): array
    {
        return $this->data;
    }

    public function formatData(): array
    {
        return $this->data;
    }

    public function render(): View|Closure|string
    {
        $this->withAttributes([
            'id' => 'layout-builder-block-'.$this->meta['uuid'] ?? null,
            'data-block-uuid' => $this->meta['uuid'] ?? null,
            'data-block-type' => static::getId(),
            'data-block-index' => $this->meta['index'] ?? null,
            'data-block-type-index' => $this->meta['type_index'] ?? null,
        ]);

        return view($this->view, [
            ...$this->formatData(),
            'meta' => $this->meta,
            'attributes' => $this->attributes,
        ]);
    }
}
