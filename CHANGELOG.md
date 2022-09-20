# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

- Changed: Strictness level constant names changed from `MINIFY_STRICTNESS_*` to `MINIFY_LEVEL_*`
- Added: Strictness level 0 (none) `MINIFY_LEVEL_NONE`, which combines source files without minfication.
- Changed: `Method::process()`/`Method::minify()` now default to `MINIFY_LEVEL_NONE` (breaking change from v1.* releases).

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