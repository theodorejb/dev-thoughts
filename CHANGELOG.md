# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2025-05-19
### Added
- New quotes from Edsger Dijkstra and Michael Feathers.
- `getDailyThought()` method.
- `getThought(int $index)` method.

### Changed
- Renamed `getDefaultThoughts()` to `getAllThoughts()`, and made it an instance method rather than static.

### Removed
- All database dependencies.
- `getFeaturedThought()` and `insertDefaultThoughts()` methods.

## [1.2.0] - 2024-01-25
### Added
- 15 new dev thoughts.

### Changed
- PHP 8.1+ is now required.

## [1.1.0] - 2022-11-06
### Added
- 29 new dev thoughts.
- Automated testing.

### Removed
- One duplicate thought and another quote that was overly wordy.

### Changed
- Corrected a couple quotes and attributions.
- `insertDefaultThoughts()` now automatically creates table if it doesn't exist and inserts missing default thoughts.
- `insertDefaultThoughts()` now inserts with a random last featured time, so that featured thoughts will be shuffled.
- Improved readme documentation.

## [1.0.0] - 2022-11-04
### Changed
- Initial stable release

[2.0.0]: https://github.com/theodorejb/dev-thoughts/compare/v1.2.0...v2.0.0
[1.2.0]: https://github.com/theodorejb/dev-thoughts/compare/v1.1.0...v1.2.0
[1.1.0]: https://github.com/theodorejb/dev-thoughts/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/theodorejb/dev-thoughts/tree/v1.0.0
