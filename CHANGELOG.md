# ChangeLog

## 0.6.0 December 27, 2025

- Enh #25: Add `normalizeAttribute()` method in `BaseAttributes` helper for normalization of attribute keys and values with support for encoding and JSON serialization, along with comprehensive tests (@terabytesoftw)

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
