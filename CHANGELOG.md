# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.0.0] - 22-09-21

### Added

- Minification level 0 (none) `MINIFY_LEVEL_NONE`, which combines source files without minfication.
- Method `toFile()`, saves output file and returns filename.
- Method `toString()`, returns minified CSS string.
- Minification level is appended as a comment to the end of `minify()` output.

### Changed

- `process()`/`minify()` now default to `MINIFY_LEVEL_NONE` (a breaking change from v1.* releases).
- Refactored `process()`
    - Splitting of "save as file" and "return as string" options into separate methods has deprecated the `$noSave` parameter (for now it shows a warning).
    - Method now returns `self` to facilitate chaining output methods.
- References, parameters and variables referring to "strictness" (`$strictness`) changed to "minification level" (`$level`).

## [1.1.1] - 22-09-20

### Added

- Demo code in `demo` folder.

### Changed

- Fixes and minor updates to README

## [1.1.0] - 22-09-18

### Added

- CHANGELOG.md
- LICENSE

### Changed

- Cleaned up code and improved comments/docblocks.
- Revised and extended README documentation.
- `$strictness` is now optional in `process()` method (defaults to `MINIFY_STRICTNESS_HIGH`).

## [1.0.0] - 2022-09-18

### Added

- Initial release.

[Unreleased]: https://github.com/lmd-code/lmdcrunchcss/compare/v1.1.0...HEAD
[1.1.0]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v1.1.0
[1.0.0]: https://github.com/lmd-code/lmdcrunchcss/releases/tag/v1.0.0