<?php

declare(strict_types=1);

namespace Outerweb\FilamentLayoutBuilder\Forms\Components;

use Filament\Actions\Action;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block as BuilderBlock;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Outerweb\FilamentLayoutBuilder\Facades\LayoutBuilder as LayoutBuilderFacade;
use Outerweb\FilamentLayoutBuilder\View\Components\Block;

class LayoutBuilder extends Builder
{
    /**
     * @var iterable{include: class-string<Block>|string, exclude: class-string<Block>|string}[]
     */
    protected iterable $filteredBlocks = [
        'include' => [],
        'exclude' => [],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(function (array $state): array {
            $dehydratedState = [];

            $index = 0;
            $typedIndexes = [];
            foreach ($state as $key => $item) {
                /** @var class-string<Block> $block */
                $blockClass = LayoutBuilderFacade::getDefinedBlockClass($item['type']);

                if (is_null($blockClass)) {
                    $dehydratedState[$key] = $item;

                    continue;
                }

                /** @var Block $block */
                $block = $blockClass::make(
                    data: $item['data']['data'] ?? [],
                    meta: $item['data']['meta'] ?? [],
                );

                $item['data']['data'] = $block
                    ->dehydrateData();
                $item['data']['meta'] = array_merge(
                    [
                        'uuid' => Str::uuid()->toString(),
                        'index' => $index,
                        'type_index' => $typedIndexes[$item['type']] ?? 0,
                    ],
                    $item['data']['meta'] ?? [],
                );

                $dehydratedState[$key] = $item;

                $index++;
                $typedIndexes[$item['type']] = ($typedIndexes[$item['type']] ?? 0) + 1;
            }

            return $dehydratedState;
        });

        $this->blocks(fn () => $this->getDefinedBuilderBlocks()->all());

        $this->addActionLabel(__('filament-layout-builder::translations.actions.add_block'));

        $this->collapsible()
            ->collapsed();

        $this->cloneable(true)
            ->cloneAction(function (Action $action): void {
                $action->action(function (array $arguments, LayoutBuilder $component) {
                    $newUuid = $component->generateUuid();

                    $items = $component->getRawState();

                    $clone = $items[$arguments['item']];
                    $clone['data']['meta']['uuid'] = Str::uuid()->toString();

                    if ($newUuid) {
                        $items[$newUuid] = $clone;
                    } else {
                        $items[] = $clone;
                    }

                    $component->rawState($items);

                    $component->collapsed(false, shouldMakeComponentCollapsible: false);

                    $component->callAfterStateUpdated();

                    $component->shouldPartiallyRenderAfterActionsCalled() ? $component->partiallyRender() : null;
                });
            });

        $this->blockNumbers(false)
            ->blockIcons(false)
            ->blockPickerColumns(LayoutBuilderFacade::getDefinedBlockClasses()->count() <= 5 ? 1 : 2)
            ->reorderableWithButtons(true);
    }

    /**
     * @param  iterable<class-string<Block>|string>  $include
     * @param  iterable<class-string<Block>|string>  $exclude
     */
    public function filteredBlocks(iterable $include = [], iterable $exclude = []): static
    {
        $this->filteredBlocks = [
            'include' => $include,
            'exclude' => $exclude,
        ];

        return $this;
    }

    /** @return Collection<BuilderBlock> */
    protected function getDefinedBuilderBlocks(): Collection
    {
        return LayoutBuilderFacade::getDefinedBlockClasses()
            ->filter(function (string $class): bool {
                if (
                    ! empty($this->filteredBlocks['exclude'] ?? [])
                    && in_array($class, $this->filteredBlocks['exclude'], true)
                ) {
                    return false;
                }

                return empty($this->filteredBlocks['include'] ?? [])
                    || in_array($class, $this->filteredBlocks['include'], true);
            })
            ->map(function (string $class) {
                /** @var class-string<Block> $class */
                return BuilderBlock::make($class::getId())
                    ->label($class::getLabel())
                    ->icon($class::getIcon())
                    ->maxItems($class::getMaxItems())
                    ->schema(function (Schema $schema) use ($class): Schema {
                        return $class::schema($schema)
                            ->components([
                                Hidden::make('meta.uuid')
                                    ->default(Str::uuid()->toString()),
                                ...$this->formatDataComponents($schema),
                            ]);
                    });
            });
    }

    private function formatDataComponents(Schema $schema): array
    {
        $components = $schema->getComponents(
            withActions: true,
            withHidden: true,
        );

        return $this->prefixStatePathRecursively($components, 'data');
    }

    private function prefixStatePathRecursively(array $components, string $prefix): array
    {
        return array_map(function ($component) use ($prefix) {
            $component->statePath($prefix.'.'.$component->getStatePath());

            if (method_exists($component, 'getComponents')) {
                $childComponents = $component->getComponents();
                $prefixedChildComponents = $this->prefixStatePathRecursively($childComponents, $prefix);
                $component->schema($prefixedChildComponents);
            }

            return $component;
        }, $components);
    }
}
