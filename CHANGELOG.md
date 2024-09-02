# Changelog

## [4.0.1] - 2024-09-02

### Fixed

- Fixed README to reference correct repo/class/etc name "PHP CSS Crunch"

## [4.0.0] - 2024-08-29

*This release contains breaking changes from previous releases.*

### Changed

- Renamed repo to "PHP CSS Crunch"
- Renamed class file/name to `PhpCssCrunch.php` (`PhpCssCrunch()`)
- Updated namespace to reflect name change.

## [3.0.2] - 2023-03-15

### Changed

- *Non-breaking change.* Static `minify()` method will no longer append the minification level/file hash comment token when used independently on CSS strings (where it is not needed anyway).

### Fixed

- Adding/removing source files now triggers minified output file regeneration by adding a file hash to the output file comment token along with the minification level. Files using the old token will be reminified.
- Various typos.

## [3.0.1] - 2022-10-17

### Fixed

- Fixed minor issues in demo/docs/README.

## [3.0.0] - 2022-10-16

### Changed

- Moved `LmdCrunchCss.php` to `src/` folder.
- Changed output file names to now allow a dot (".") as the first character.
- Changed path requirement for both source and output files to be the absolute path from the document root (instead of the full file-system path).
- Changed the `toFile()` return value to be the stylesheet `<link>` markup (using `getMarkup()`).
- README has been rewritten and updated.

### Added

- Added `getMarkup()` method that renders stylesheet `<link>` markup.
- Added `$docRoot` (absolute file-system path to document root) param to constructor method.
- Added `$devMode` (development mode) param to constructor method.
- Added `$bustCache` (cache buster) param to `toFile()` method (to use with `getMarkup()`).

### Removed

- Removed `$noSave` param from `process()` method.

## [2.0.1] - 2022-09-27

### Fixed

- Fixed the `minify()` method, it no longer returns errors if `$css` param is empty.

## [2.0.0] - 2022-09-21

*This release contains breaking changes from previous releases.*

### Changed

- Changed `process()`/`minify()` default to `MINIFY_LEVEL_NONE`.
- Changed `process()` to be chainable and deprecated the `$noSave` parameter (for now it shows a warning).
- Changed references, parameters and variables referring to "strictness" (`$strictness`) to "minification level" (`$level`).

### Added

- Added minification level 0 (none) `MINIFY_LEVEL_NONE`, which combines source files without minification.
- Added method `toFile()` which saves output file and returns filename.
- Added method `toString()` which returns minified CSS string.
- Added minification level to minified output as an appended comment.

## [1.1.1] - 2022-09-20

### Changed

- Fixes and minor updates to README.

### Added

- Added demo code.

## [1.1.0] - 2022-09-18

### Changed

- Changed `$strictness` param to be optional in `process()` method (defaults to `MINIFY_STRICTNESS_HIGH`).
- Revised and extended README documentation.

## [1.0.0] - 2022-09-18

*First release.*

[4.0.1]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v4.0.1
[4.0.0]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v4.0.0
[3.0.2]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v3.0.2
[3.0.1]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v3.0.1
[3.0.0]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v3.0.0
[2.0.1]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v2.0.1
[2.0.0]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v2.0.0
[1.1.1]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v1.1.1
[1.1.0]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v1.1.0
[1.0.0]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v1.0.0
