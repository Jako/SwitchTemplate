# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.3.2] - 2022-06-10

### Changed

- Update Composer dependencies
- Decrease the timeout in the statistics resolver
- Fix wrong classname

### Security

- Update guzzlehttp/guzzle to 6.5.7 for [security reasons](https://github.com/guzzle/guzzle/security/advisories)

## [1.3.1] - 2022-02-17

### Fixed

- Fix strrpos warning: Offset is greater than the length of haystack string
- Fix PHP notice: Undefined property: SwitchTemplate::$fromCache

## [1.3.0] - 2022-01-11

### Changed

- Code refactoring
- Full MODX 3 compatibility

## [1.2.5] - 2020-12-29

### Fixed

- Bugfix for not found switching extensions

## [1.2.4] - 2020-11-19

### Added

- Add static file support for templates (Thanks to [David Pede](https://github.com/davidpede)) [#10]

## [1.2.3] - 2019-02-23

### Added

- Show debug mode in custom manager page
- System settings tab in custom manager page

### Changed

- Log errors in debug mode only

## [1.2.2] - 2018-02-26

### Fixed

- Bugfix for an empty output type

## [1.2.1] - 2017-12-04

### Added

- Make the extension based template switch work with xRouting, LangRouter and other routing extras
- Enable debug information in the frontend with a system setting and an URL parameter

## [1.2.0] - 2017-09-12

### Added

- Switch the template on base of the extension
- $modx->RegClient methods are executed in switched templates with HTML output
- Filter the page source before output i.e. for AMP output
- Absolute links & images and image width & height attributes in AMP output
- Switched template/chunk name on base of the original template name
- Set the allowed SwitchTemplate modes in a template variable on resource base

## [1.1.1] - 2015-03-12

### Added

- Switch back to default template if the chunk/template is not found
- Template name could be set by placeholder too

## [1.1.0] - 2015-03-10

### Added

- Chunk name for chunk template type could be set by placeholder

## [1.0.1] - 2014-10-21

### Changed

- Processed placeholders are now cached

## [1.0.0] - 2014-09-27

### Added

- Initial public release
