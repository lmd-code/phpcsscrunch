# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

- Changed: Strictness level constant names changed from `MINIFY_STRICTNESS_*` to `MINIFY_LEVEL_*`
- Added: Strictness level 0 (none) `MINIFY_LEVEL_NONE`, which combines source files without minfication.
- Changed: `Method::process()`/`Method::minify()` now default to `MINIFY_LEVEL_NONE` (breaking change from v1.* releases).
- Changed: Renamed private methods `openSourceFile()` and `saveOutputFile()` to `readFile()` and `saveFile()`.
- Changed: Refactored `process()`
    - Improved the way method detemines whether to read/process source files or use ssaved output.
    - Split "save as file" and "return as string" options into separate methods (and deprecated the $noSave parameter).
    - Method now always returns `self` to facilitate chaining output methods.
- Added: Method `toFile()`, returns filename.
- Added: Method `toString()`, returns minified CSS string.
- Added: Property `$rawCss` to store the source CSS, enabling output of multple different minified versions of the same source without having to read the files every time.
- Added: Property `$lastStrictness` to store the last applied strictness level, enabling the same minified source to be requested in different formats (e.g. as a string and a file) without needing to minify again.
- Added: The minification strictness level is appended as a comment to the end of `minify()` output and is used to determine previous minification level when reading an already saved output file.

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