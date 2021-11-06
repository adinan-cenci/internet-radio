# Changelog
All notable changes to this project will be documented in this file.

## [2.0.0] - 2021-11-06

## Changed

- Previously `InternetRadio::getStationsByGenre()` and `InternetRadio::searchStations()` accepted 
the `$pageOffset` and `$pageLimit` parameters and returned all the results in this range of pages.
Now those were replaced with the absolute `$offset` and `$limit` parameters respectively.

- Improvements were made to the scraping mechanism.

## [1.1.1] - 2021-11-01

### Changed

Added `guzzlehttp/guzzle` to the project, replacing my own `adinan-cenci/simple-request`.

## [1.1.0] - 2021-01-23
### Added
- Radio stations now include an unique identifier in the form of the "id" key.
