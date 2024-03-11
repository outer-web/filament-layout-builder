# Filament Layout Builder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/outerweb/filament-layout-builder.svg?style=flat-square)](https://packagist.org/packages/outerweb/filament-layout-builder)
[![Total Downloads](https://img.shields.io/packagist/dt/outerweb/filament-layout-builder.svg?style=flat-square)](https://packagist.org/packages/outerweb/filament-layout-builder)

This package extends the filament builder field to work with predefined layout blocks to build the content of a page.

## Installation

You can install the package via composer:

```bash
composer require outerweb/filament-layout-builder
```

Add the plugin to your desired Filament panel:

```php
use OuterWeb\FilamentLayoutBuilder\Filament\FilamentLayoutBuilder;

class FilamentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // ...
            ->plugins([
                FilamentLayoutBuilderPlugin::make(),
            ]);
    }
}
```

## Usage

### Creating layout blocks

Create all your layout blocks in the `app/View/Components/LayoutBuilder` directory. Each layout block should extend the `Outerweb\FilamentLayoutBuilder\View\Components\Block` class.

```php
namespace App\View\Components\LayoutBuilder;

use Outerweb\FilamentLayoutBuilder\View\Components\Block;

class Article extends Block
{
    // ...
}
```

#### Define the view

You can define which view to render by setting the `$view` property.

```php
class Article extends Block
{
    protected string $view = 'components.layout-builder.article';
}
```

#### Define the fields for the Filament form

You can define the fields for the Filament form by adding a `schema` method to the block.

```php
class Article extends Block
{
    // ...

    public function schema(): array
    {
        return [
            // ...
        ];
    }
}
```

#### Formatting data / Fetching data from the database

You can define a `formatData` method on your block to format the data before it is passed to the view.
This can be useful to fetch data from the database or to format the data before it is passed to the view.

```php
class Article extends Block
{
    // ...

    public function formatData(array $data): array
    {
        $data['images'] = Image::whereIn('id', $data['images'] ?? [])->get();

        return $data;
    }
}
```

### Using the make command

You can use the `make:layout-builder-block` command to generate a new layout builder block.

```bash
php artisan make:layout-builder-block Article
```

This will generate a new layout builder block in the `app/View/Components/LayoutBuilder` directory: `app/View/Components/LayoutBuilder/Article.php`.

And a new view in the `resources/views/components/layout-builder` directory: `resources/views/components/layout-builder/article.blade.php`.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Simon Broekaert](https://github.com/SimonBroekaert)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
