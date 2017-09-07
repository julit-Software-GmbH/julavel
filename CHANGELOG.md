# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added

- BuiltinServer trait which allows to run dusk tests using the php builtin
  server. As a benefit browser tests can be included into the main file.

- ElasticIndices trait which deletes all indices of the current prefix.

- RefreshStorage trait which deletes all files in the storage.

- TemporaryStorage trait which creates a temporary in memory storage
  for each test.

- TruncateDatabase trait which resets all tables excluding migrations to
  get a fresh database in dusk tests.

- TestCase and DuskTestCase as entry points
