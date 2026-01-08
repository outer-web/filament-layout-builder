<?php

declare(strict_types=1);

namespace Outerweb\FilamentLayoutBuilder\Commands;

use Illuminate\Foundation\Console\ComponentMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeLayoutBuilderBlockCommand extends ComponentMakeCommand
{
    public $description = 'Create a new layout builder block';

    protected $name = 'make:filament-layout-builder-block';

    public function handle(): void
    {
        if (get_parent_class(get_parent_class($this))::handle() === false && ! $this->option('force')) {
            return;
        }

        $this->writeView();
    }

    protected function writeView($onSuccess = null): void
    {
        $path = $this->viewPath(
            str_replace('.', '/', 'components.layout-builder.'.$this->getView()).'.blade.php'
        );

        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        if ($this->files->exists($path) && ! $this->option('force')) {
            $this->components->error('View already exists.');

            return;
        }

        file_put_contents(
            $path,
            '<section {{ $attributes }}>
    {{-- Build something beautiful! --}}
</section>'
        );

        if ($onSuccess) {
            $onSuccess();
        }
    }

    protected function buildClass($name): array|string
    {
        return str_replace(
            ['DummyView', '{{ view }}'],
            $this->getView(),
            get_parent_class(get_parent_class($this))::buildClass($name)
        );
    }

    protected function getView(): string
    {
        $name = str_replace('\\', '/', $this->argument('name'));

        return collect(explode('/', $name))
            ->map(function ($part): string {
                return Str::kebab($part);
            })
            ->implode('.');
    }

    protected function getStub(): string
    {
        return __DIR__.'/../../stubs/class.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\View\Components/LayoutBuilder';
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the component already exists'],
            ['inline', null, InputOption::VALUE_NONE, 'Create a component that renders an inline view'],
            ['view', null, InputOption::VALUE_NONE, 'Create an anonymous component with only a view'],
        ];
    }
}
