![Filament Layout Builder](./docs/images/github-banner.png)

# Filament Layout Builder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/outerweb/filament-layout-builder.svg?style=flat-square)](https://packagist.org/packages/outerweb/filament-layout-builder)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/outerweb/filament-layout-builder/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/outer-web/filament-layout-builder/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/outerweb/filament-layout-builder/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/outer-web/filament-layout-builder/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/outerweb/filament-layout-builder.svg?style=flat-square)](https://packagist.org/packages/outerweb/filament-layout-builder)

This Filament plugins adds a LayoutBuilder Form Component to build layouts using custom building blocks.

## Table of Contents

-   [Installation](#installation)
-   [Usage](#usage)
-   [Changelog](#changelog)
-   [License](#license)

## Installation

You can install the package via composer:

```bash
composer require outerweb/filament-layout-builder
```

Add the plugin to your panel:

```php
use Outerweb\FilamentLayoutBuilder\LayoutBuilderPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->plugins([
            // ...
            LayoutBuilderPlugin::make(),
        ]);
}
```

## Usage

### Creating LayoutBuilder Blocks

You can easily create a LayoutBuilder block by running the following command:

```bash
php artisan make:filament-layout-builder-block
```

This will generate the following:

-   A View Component class in `App\Views\Components\LayoutBuilder`
-   A Blade view file in `resources/views/components/layout-builder`

#### The View Component class

The generated View Component class will look like this:

```php
<?php

declare(strict_types=1);

namespace App\View\Components\LayoutBuilder;

use Filament\Schemas\Schema;
use Outerweb\FilamentLayoutBuilder\View\Components\Block;

class Article extends Block
{
    protected string $view = 'components.layout-builder.article';

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
}
```

You can define the schema for your block in the `schema` method using Filament's form components. The `dehydrateData` method is used to prepare the data for storage, while the `formatData` method is used to format the data for rendering in the view.

#### The Blade view file

The generated Blade view file will look like this:

```blade
<section {{ $attributes }}>
	{{-- Build something beautiful! --}}
</section>
```

All array keys returned from the `formatData` method will be available as variables in this view.

### Adding the LayoutBuilder to your forms

You can add the LayoutBuilder to your Filament resource forms like this:

```php
use Outerweb\FilamentLayoutBuilder\Form\Components\LayoutBuilder;

public static function form(Form $form): Form
{
    return $form
        ->schema([
            LayoutBuilder::make('layout'),
        ]);
}
```

The blocks will automatically discovered and made available in the LayoutBuilder.

You can also include/exclude specific blocks like this:

```php
// Only include specific blocks
LayoutBuilder::make('layout')
    ->filteredBlocks(
        include: [
            \App\View\Components\LayoutBuilder\Article::class,
        ],
    ),

// Exclude specific blocks
LayoutBuilder::make('layout')
    ->filteredBlocks(
        exclude: [
            \App\View\Components\LayoutBuilder\Article::class,
        ],
    ),
```

### Rendering the LayoutBuilder in your views

You can render the LayoutBuilder in your views like this:

```blade
<x-filament-layout-builder-container :blocks="$record->layout" />
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
