# ChangeLog

## 0.6.5 January 20, 2026

- Bug #36: Move directory `tests\Providers` to `tests\Support\Provider` for better organization (@terabytesoftw)
- Enh #37: Add usage examples for HTML helper methods, including attribute normalization and SVG offset validation (@terabytesoftw)
- Bug #38: Update testing documentation for clarity and organization (@terabytesoftw)
- Enh #39: Add development guide for workflows and maintenance tasks (@terabytesoftw)
- Enh #40: Add `php-forge/support` as a development dependency and update related test classes (@terabytesoftw)
- Bug #41: Enhance documentation for `Message` enum to clarify error message templates and formatting usage (@terabytesoftw)

## 0.6.4 January 18, 2026

- Bug #31: Update configuration files and improve code structure for better maintainability and update related classes (@terabytesoftw)
- Bug #32: Enhance documentation and improve clarity across multiple classes and methods (@terabytesoftw)
- Bug #33: Enhance documentation tests and improve clarity across multiple test files (@terabytesoftw)
- Enh #34: Add `offsetLike()` method in `BaseValidator` class for validating offset-like numbers with optional minimum and maximum constraints, along with related tests (@terabytesoftw)
- Bug #35: Update documentation in test files and providers for clarity and consistency (@terabytesoftw)

## 0.6.3 January 3, 2026

- Bug #30: Remove unnecessary `strtolower()` usage and update test cases for consistent casing (@terabytesoftw)

## 0.6.2 January 1, 2026

- Enh #29: Update `positiveLike()` method to validate within a specified range and adjust related tests (@terabytesoftw)

## 0.6.1 December 31, 2025

- Enh #27: Add `positiveLike()` method in `BaseValidator` for validating positive-like numbers with optional maximum constraint, along with related tests (@terabytesoftw)
- Enh #28: Enhance `positiveLike()` method to include minimum value validation and update related tests (@terabytesoftw)

## 0.6.0 December 27, 2025

- Enh #25: Add `normalizeAttributes()` method in `BaseAttributes` helper for normalization of attribute keys and values with support for encoding and JSON serialization, along with comprehensive tests (@terabytesoftw)
- Bug #26: Rename exception test methods to clarify expected exceptions (@terabytesoftw)

## 0.5.3 December 26, 2025

- Bug #24: Update argument types in `oneOf` method and related tests to support `UnitEnum` in `argumentName` parameter (@terabytesoftw)

## 0.5.2 December 26, 2025

- Bug #21: Update group annotation from 'helpers' to 'helper' across multiple test files (@terabytesoftw)
- Dep #22: Update `infection/infection` version constraint to `^0.32` in `composer.json` (@terabytesoftw)
- Bug #23: Allow `null` values in `oneOf` method and update related tests (@terabytesoftw)

## 0.5.1 December 23, 2025

- Enh #20: Add `normalizeKey()` method in `BaseAttributes` helper for normalization attribute keys with prefixes, enhance error handling and add related tests (@terabytesoftw)

## 0.5.0 December 19, 2025

- Bug #15: Update copyright year to `2024` in `LICENSE` file (@terabytesoftw)
- Bug #16: Update image alt text from `Yii Framework` to `UI Awesome` (@terabytesoftw)
- Bug #17: Enhance support for `Stringable` interface types across `BaseCssClass`, `BaseEncode`, `BaseEnum`, `BaseValidator` classes and update related tests (@terabytesoftw)
- Bug #18: Update feature descriptions in SVG files to reflect new capabilities including `Stringable` support and performance optimizations (@terabytesoftw)
- Bug #19: Remove trailing periods from alert content in SVG feature descriptions (@terabytesoftw)

## 0.4.0 December 17, 2025

- Bug #13: Update `CHANGELOG.md` for version `0.4.0` and remove `ui-awesome/html-interop` dependencies in `composer.json` (@terabytesoftw)
- Bug #14: Add missing `phpstan/phpstan` dependency in `composer.json` (@terabytesoftw)

## 0.3.0 December 16, 2025

- Enh #8: Refactor codebase to improve performance (@terabytesoftw)
- Bug #9: Enhance documentation and improve clarity across HTML helper classes (@terabytesoftw)
- Bug #10: Improve pattern handling and enhance test coverage for `Naming` class (@terabytesoftw)
- Bug #11: Add `Enum` helper for value normalization and support for arrays and `UnitEnum` instance (@terabytesoftw)
- Bug #12: Simplify descriptions for HTML helper features in `README.md` (@terabytesoftw)

## 0.2.0 March 30, 2024

- Enh #7: Move `HTMLBuilder::class` to `ui-awesome/html-core` package (@terabytesoftw)

## 0.1.2 March 25, 2024

- Enh #6: Add method `render()` in `CssClass::class` (@terabytesoftw)

## 0.1.1 March 9, 2024

- Bug #5: Change branch alias to `1.0-dev` in `composer.json` (@terabytesoftw)

## 0.1.0 March 6, 2024

- Enh #1: Initial commit (@terabytesoftw)
- Enh #2: Add helper `Template::class` (@terabytesoftw)
- Bug #3: Fix `README.md` (@terabytesoftw)
- Enh #4: Add helper `HTMLBuilder::class` (@terabytesoftw)
