<?php

namespace Outerweb\FilamentLayoutBuilder\Console\Commands;

use GeneratorCommand;
use Illuminate\Foundation\Console\ComponentMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class LayoutBuilderBlockMakeCommand extends ComponentMakeCommand
{
    protected $name = 'make:layout-builder-block';

    protected $description = 'Create a new layout builder block';

    public function handle()
    {
        if (get_parent_class(get_parent_class($this))::handle() === false && ! $this->option('force')) {
            return false;
        }

        $this->writeView();
    }

    protected function writeView($onSuccess = null)
    {
        $path = $this->viewPath(
            str_replace('.', '/', 'components.layout-builder.' . $this->getView()) . '.blade.php'
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
    // Build something beautiful!
</section>'
        );

        if ($onSuccess) {
            $onSuccess();
        }
    }

    protected function buildClass($name)
    {
        return str_replace(
            ['DummyView', '{{ view }}'],
            $this->getView(),
            get_parent_class(get_parent_class($this))::buildClass($name)
        );
    }

    protected function getView()
    {
        $name = str_replace('\\', '/', $this->argument('name'));

        return collect(explode('/', $name))
            ->map(function ($part) {
                return Str::kebab($part);
            })
            ->implode('.');
    }

    protected function getStub()
    {
        return __DIR__ . '/../../Stubs/class.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\View\Components/LayoutBuilder';
    }

    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the component already exists'],
            ['inline', null, InputOption::VALUE_NONE, 'Create a component that renders an inline view'],
            ['view', null, InputOption::VALUE_NONE, 'Create an anonymous component with only a view'],
        ];
    }
}
