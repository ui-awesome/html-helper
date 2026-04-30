# Changelog

All notable changes to this project will be documented in this file.

The format is based on Keep a Changelog, and this project adheres to Semantic Versioning.

## 0.7.4 Under development

- docs: update `README.md` to reflect the latest version of the package.
- chore: refine `composer.json` description and keywords to reflect the actual helper surface (attributes, CSS class, encoding, sanitization, template normalization).
- docs: correct capitalization in the main heading of `README.md`.
- chore: update workflow actions to use version `v1` instead of main.
- docs: add `UPGRADE.md` notes for backward compatibility changes.

## 0.7.3 April 28, 2026

- feat: add `AttributeBag::replace()` and remove unsafe attribute merge behavior while normalizing `null` as removal.
- fix: remove attributes from `AttributeBag` when `set()` receives `null` or a closure resolving to `null`.
- feat: add `Enum::normalizeStringValue()` and `Enum::normalizeStringArray()` for deterministic string representations.
- docs: refresh feature overview SVGs with the current helper capabilities.

## 0.7.2 February 15, 2026

- feat: move `normalizeKey()` to `AttributeBag` and add optional `$prefix` support to `get()`, `remove()`, `set()`, and `setMany()`.
- docs: update `README.md` to reflect `AttributeBag` helper usage and examples.

## 0.7.1 February 15, 2026

- fix: serialize boolean values in `aria-*`, `data-*`, `data-ng`, `ng-*`, and `on*` attributes as explicit strings.
- fix: support closure values in attribute handling and add a test case for boolean closure values.

## 0.7.0 February 14, 2026

- feat: simplify `AttributeBag` to generic `set()` and `setMany()` operations, remove `add()`, and keep `Attributes` responsible for `aria`, `data`, and `on` expansion during rendering.

## 0.6.9 February 14, 2026

- feat: add helper `AttributeBag` class to centralize `add()`, `get()`, `merge()`, `remove()`, and `set()` operations with unit tests.
- docs: standardize PHPDoc across `src` for consistent API documentation.
- docs: standardize PHPDoc across `tests` for consistent test documentation.

## 0.6.8 February 11, 2026

- feat: add helper `LineBreakNormalizer` class for normalizing line breaks in strings.

## 0.6.7 January 28, 2026

- docs: update `testing.md` examples for running Composer scripts with arguments and align `.styleci.yml` accordingly.
- chore: remove the redundant ignore rule in `actionlint.yml` and update the Rector command in `composer.json` to remove the unnecessary `src` argument.

## 0.6.6 January 24, 2026

- chore: add `php-forge/coding-standard` to development dependencies for code quality checks.

## 0.6.5 January 20, 2026

- test: move `tests/Providers` to `tests/Support/Provider` for better organization.
- docs: add usage examples for HTML helper methods, including attribute normalization and SVG offset validation.
- docs: update testing documentation for clarity and organization.
- docs: add development guide for workflows and maintenance tasks.
- chore: add `php-forge/support` as a development dependency and update related test classes.
- docs: clarify `Message` enum error message templates and formatting usage.

## 0.6.4 January 18, 2026

- refactor: update configuration and related classes to improve maintainability.
- docs: enhance documentation across multiple classes and methods.
- docs: enhance documentation tests across multiple test files.
- feat: add `BaseValidator::offsetLike()` for validating offset-like numbers with optional minimum and maximum constraints.
- docs: update documentation in test files and providers for clarity and consistency.

## 0.6.3 January 3, 2026

- fix: remove unnecessary `strtolower()` usage and update test cases for consistent casing.

## 0.6.2 January 1, 2026

- feat: update `BaseValidator::positiveLike()` to validate values within a specified range and adjust related tests.

## 0.6.1 December 31, 2025

- feat: add `BaseValidator::positiveLike()` for validating positive-like numbers with an optional maximum constraint.
- feat: add minimum value validation to `BaseValidator::positiveLike()` and update related tests.

## 0.6.0 December 27, 2025

- feat: add `BaseAttributes::normalizeAttributes()` for normalizing attribute keys and values with encoding and JSON serialization support.
- test: rename exception test methods to clarify expected exceptions.

## 0.5.3 December 26, 2025

- fix: allow `UnitEnum` in the `oneOf()` `$argumentName` parameter and update related tests.

## 0.5.2 December 26, 2025

- test: update group annotation from `helpers` to `helper` across multiple test files.
- chore: update `infection/infection` version constraint to `^0.32` in `composer.json`.
- fix: allow `null` values in `oneOf()` and update related tests.

## 0.5.1 December 23, 2025

- feat: add `BaseAttributes::normalizeKey()` for normalizing attribute keys with prefixes, enhance error handling, and add related tests.

## 0.5.0 December 19, 2025

- fix: update the copyright year to `2024` in `LICENSE`.
- docs: update image alt text from `Yii Framework` to `UI Awesome`.
- feat: enhance `Stringable` support across `BaseCssClass`, `BaseEncode`, `BaseEnum`, and `BaseValidator`.
- docs: update feature descriptions in SVG files to reflect `Stringable` support and performance optimizations.
- docs: remove trailing periods from alert content in SVG feature descriptions.

## 0.4.0 December 17, 2025

- chore: update `CHANGELOG.md` for version `0.4.0` and remove `ui-awesome/html-interop` dependencies from `composer.json`.
- chore: add missing `phpstan/phpstan` dependency to `composer.json`.

## 0.3.0 December 16, 2025

- refactor: improve codebase performance.
- docs: enhance documentation across HTML helper classes.
- fix: improve pattern handling and enhance test coverage for `Naming`.
- feat: add `Enum` helper for value normalization with array and `UnitEnum` support.
- docs: simplify HTML helper feature descriptions in `README.md`.

## 0.2.0 March 30, 2024

- feat: move `HTMLBuilder` to the `ui-awesome/html-core` package.

## 0.1.2 March 25, 2024

- feat: add `CssClass::render()`.

## 0.1.1 March 9, 2024

- fix: change the branch alias to `1.0-dev` in `composer.json`.

## 0.1.0 March 6, 2024

- feat: initial `ui-awesome/html-helper` package structure.
- feat: add helper `Template`.
- fix: correct `README.md`.
- feat: add helper `HTMLBuilder`.
