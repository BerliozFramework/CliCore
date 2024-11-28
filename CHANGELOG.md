# Change Log

All notable changes to this project will be documented in this file. This project adheres
to [Semantic Versioning] (http://semver.org/). For change log format,
use [Keep a Changelog] (http://keepachangelog.com/).

## [2.2.1] - 2024-11-28

### Fixed

- `Environment::isArgumentDefined()` return false in case of handler called with $argv value

## [2.2.0] - 2022-12-02

### Added

- New arguments for clear cache command to clear all directories or specified

## [2.1.1] - 2022-03-23

### Fixed

- CHMOD of executable command

## [2.1.0] - 2022-01-13

### Added

- New method `Environment::getArgumentMultiple()` to get values for an argument multiple

## [2.0.0] - 2021-09-08

No changes were introduced since the previous beta 2 release.

## [2.0.0-beta2] - 2021-07-07

### Changed

- Declare `CliApp` inflector to the service container in constructor

### Removed

- Remove `CliApp` dependency in constructor of `AbstractCommand`, inflector used instead

## [2.0.0-beta1] - 2021-06-07

### Added

- Console with `league/climate` library

### Changed

- Namespace move from `Berlioz\CliCore` to `Berlioz\Cli\Core`
- Refactoring
- Bump minimal compatibility to PHP 8

### Removed

- Dependency to `psr/log`
- Dependency to `ulrichsg/getopt-php`

## [1.1.0] - 2020-11-05

### Added

- PHP 8 compatibility in `composer.json`

### Changed

- Bump `ulrichsg/getopt-php` required package version in `composer.json`

## [1.0.0] - 2020-05-29

First version
