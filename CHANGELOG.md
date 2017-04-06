# Changelog
All notable changes to this project will be documented in this file.

## [4.0.4] - 2017-04-06

### Changed
- added php7 support. fixed contao-core dependency

## [4.0.4] - 2017-03-17

### Fixed
- backend css for form group container

## [4.0.3] - 2017-03-17

### Fixed
- table name issues #2

## [4.0.2] - 2017-03-17

### Fixed
- table name issues

## [4.0.1] - 2017-03-17

### Fixed
- composer.json

## [4.0.0] - 2017-03-17

### Added
- removed multiColumnWizard dependency -> replaced by multi_column_editor -> joinTables needs to be set again, headerFieldLabels should work out of the box

### Fixed
- fieldDelimiter is now respected for csv again
- column matching when using joins

## [3.0.10] - 2017-03-13

### Fixed
- Remove unqualified id from SELECT statement to avoid sql ambiguous error on joins 

## [3.0.9] - 2017-02-21

### Fixed
- media exporter filename generation

## [3.0.8] - 2017-02-21

### Fixed
- media exporter

## [3.0.7] - 2017-01-18

### Fixed
- tweaked performance
- replaced array() by []