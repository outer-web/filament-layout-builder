# Changelog

All notable changes to `filament-layout-builder` will be documented in this file.

## 1.8.0 - 2025-03-04

### Fixed

- Fixed issue with clone() action where the block id was not unique for the cloned block(s).

## 1.7.0 - 2025-02-27

### Added

- Added support for Laravel 12.

## 1.6.0 - 2024-03-24

### Fixed

- Fixed issue where passing a `null` value to the `container` blade component would throw an error.

## 1.5.0 - 2024-03-19

### Fixed

- Fixed issue with `type_index` in the block data.

## 1.4.0 - 2024-03-18

### Fixed

- Fixed issues with blade component `container` not being able to render the blocks.

## 1.3.0 - 2024-03-13

### Fixed

- Fixed issue where saving the layout blocks would not work.
- Fixed issue where blocks with translations would not get deleted completely.

### Changed

- Removed the blockNumbers.

## 1.2.0 - 2024-03-12

### Fixed

- Fixed issue where label was not being used.

## 1.1.1 - 2024-03-12

### Added

- Added support for Laravel 11.

## 1.1.0 - 2024-03-12

### Added

- Added `make:layout-builder-block` command to generate a new layout builder block.

## 1.0.0 - 2024-03-03

- Initial release
