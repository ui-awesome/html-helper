<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use InvalidArgumentException;
use UIAwesome\Html\Helper\{Enum, Validator};
use UnitEnum;

use function array_unique;
use function implode;
use function is_array;
use function is_string;
use function preg_match;
use function preg_split;
use function sprintf;

/**
 * Base class for safe and flexible CSS class manipulation.
 *
 * Provides a fluent, immutable API for handling CSS class attributes, supporting `BackedEnum` integration, base class
 * formatting, class list merging, and robust validation for safe HTML output.
 *
 * Designed for use in HTML helpers, tags, and view renderers, this class ensures that CSS class attributes are
 * constructed, merged and validated according to modern standards and security best practices.
 *
 * Key features.
 * - Attribute array manipulation for class merging and overrides.
 * - `BackedEnum` and `UnitEnum` support for type-safe class definitions.
 * - Base class formatting and normalization.
 * - Integration-ready for asset, tag, and view systems.
 * - Stateless, static API for safe reuse.
 * - Strict CSS class name validation using regex.
 *
 * {@see InvalidArgumentException} for invalid value errors.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseCSSClass
{
    /**
     * Regular expression pattern for validating modern CSS class names.
     *
     * Validates CSS class names compatible with modern CSS frameworks and utility-first approaches, ensuring safe and
     * predictable HTML rendering while supporting advanced naming conventions.
     *
     * Permitted characters.
     * - **Letters** (a-z, A-Z, Unicode letters): Standard CSS identifier characters and international support.
     * - **Digits** (0-9): Numeric variants, responsive breakpoints, and utility scales.
     * - **Colons** (:): Pseudo-class modifiers and state variants (for example, `hover:`, `focus:`, `lg:`).
     * - **Dots** (.): Utility class notation and namespace separators.
     * - **Hyphens** (-): Kebab-case naming convention and compound class names.
     * - **Underscores** (_): Alternative word separators and BEM methodology support.
     * - **Square brackets** ([]): Arbitrary value syntax for dynamic utilities (for example, `w-[200px]`,
     *   `bg-[#1da1f2]`, `w-[calc(100%-2rem)]`).
     * - **Additional characters**: Hash (#) for colors, percent (%) for relative values, parentheses (()) for CSS
     *   functions like `calc()`, `min()`, `max()`, plus (+) and slash (/) for calculations and ratios, single quotes
     *   (') for content values, equals (=) for attribute selectors, commas (,) for multiple values.
     * - **Unicode characters**: Full Unicode support for internationalization (stars ★, arrows →, etc.).
     *
     * Excluded characters for security and safety.
     * - Whitespace characters (space, tab, newline) - would break CSS class parsing.
     * - At symbol (@) - reserved for CSS at-rules.
     * - Exclamation mark (!) - could conflict with important declarations.
     * - Semicolon (;) - could break CSS syntax.
     * - Angle brackets (<, >) - XSS prevention.
     * - Quotes (", `) - potential injection vectors (single quote ' is allowed for content).
     * - Curly braces ({}) - breaks CSS syntax.
     * - Pipe (|) - reserved for attribute selectors.
     * - Backslash (\) - escape character conflicts.
     * - Caret (^) - reserved for attribute selectors.
     * - Ampersand (&) - reserved for SCSS nesting.
     * - Dollar sign ($) - reserved for attribute selectors.
     * - Asterisk (*) - reserved for universal selector.
     *
     * This pattern supports modern CSS frameworks such as Tailwind CSS, Bootstrap 5+, and custom utility systems, while
     * maintaining compatibility with traditional CSS naming conventions and internationalization requirements.
     *
     * Note: Empty strings are rejected during validation. The pattern requires at least one character and does not
     * validate CSS selector syntax rules. The pattern uses Unicode mode (u flag) for proper international character
     * support. This pattern uses a negative character class approach to explicitly exclude dangerous characters while
     * supporting Unicode symbols needed for internationalization.
     *
     * @see https://www.w3.org/TR/CSS21/syndata.html#characters CSS 2.1 Characters and case specification.
     */
    private const VALID_CSS_CLASS_PATTERN = '/^[^\s@!;<>"{}|^&*`\\\\$]+$/u';

    /**
     * Adds one or more CSS classes to an attribute array with validation and merging logic.
     *
     * This method provides a flexible way to add CSS classes to an HTML attribute array, supporting a variety of input
     * types (arrays, enums, strings, or `null`) and ensuring that all class names are validated for CSS compliance.
     *
     * If `$override` is `true`, any existing `class` attribute is replaced; otherwise, new classes are merged with
     * existing ones, preserving uniqueness and order.
     *
     * @param array $attributes Attribute array to modify. Passed by reference and updated in place.
     * @param array|string|UnitEnum|null $classes Classes to add.
     * @param bool $override Whether to override (`true`) or merge (`false`, default) with existing classes.
     *
     * @phpstan-param mixed[] $attributes
     * @phpstan-param mixed[]|string|UnitEnum|null $classes
     *
     * Usage example:
     * ```php
     * // default
     * $attrs = ['id' => 'main'];
     * CSSClass::add($attrs, ['btn', 'btn-primary']);
     * // $attrs = ['id' => 'main', 'class' => 'btn btn-primary']
     *
     * // override existing classes
     * CSSClass::add($attrs, 'alert alert-danger', true);
     * // $attrs = ['id' => 'main', 'class' => 'alert alert-danger']
     * ```
     */
    public static function add(array &$attributes, array|string|UnitEnum|null $classes, bool $override = false): void
    {
        $normalizedClasses = self::normalizeClasses($classes);

        if ($normalizedClasses === []) {
            return;
        }

        if ($override) {
            $attributes['class'] = implode(' ', $normalizedClasses);
        } else {
            $existingClasses = [];

            if (
                isset($attributes['class']) &&
                (
                    is_array($attributes['class']) ||
                    is_string($attributes['class']) ||
                    $attributes['class'] instanceof UnitEnum
                )
            ) {
                $existingClasses = self::normalizeClasses($attributes['class']);
            }

            $attributes['class'] = implode(' ', array_unique([...$existingClasses, ...$normalizedClasses]));
        }
    }

    /**
     * Renders a validated CSS class name using a base format and an allowed value list.
     *
     * Validates the provided class value against a list of allowed options, ensuring only permitted CSS classes are
     * rendered.
     *
     * @param string|UnitEnum $class Class value to validate and render.
     * @param string $baseClass Base class format string (for example, `btn-%s`).
     * @param array $allowed List of allowed class values for validation.
     *
     * @throws InvalidArgumentException if the class value is not in the allowed list.
     *
     * @return string Formatted and validated CSS class name.
     *
     * {@see InvalidArgumentException} for invalid argument errors.
     * {@see Enum::normalizeValue()} for enum normalization logic.
     *
     * @phpstan-param list<scalar|UnitEnum|null> $allowed
     *
     * Usage example:
     * ```php
     * // using enum
     * $class = CSSClass::render(ButtonType::PRIMARY, 'btn-%s', ButtonType::values());
     * // return 'btn-primary'
     *
     * // using string
     * $class = CSSClass::render('secondary', 'btn-%s', ['primary', 'secondary']);
     * // returns 'btn-secondary'
     *
     * // throws exception for invalid value
     * CSSClass::render('danger', 'btn-%s', ['primary', 'secondary']);
     * ```
     */
    public static function render(string|UnitEnum $class, string $baseClass, array $allowed): string
    {
        $value = Enum::normalizeValue($class);

        Validator::oneOf($value, $allowed, 'class');

        return sprintf($baseClass, $value);
    }

    /**
     * Extracts a string value from a `UnitEnum` or string item.
     *
     * @param mixed $item Item to extract string value from (`UnitEnum`, string, or other).
     *
     * @return string|null Extracted string value, or `null` if item is invalid or not a string type.
     */
    private static function extractStringValue(mixed $item): string|null
    {
        if (is_string($item)) {
            return $item;
        }

        if ($item instanceof UnitEnum) {
            $value = Enum::normalizeValue($item);

            return is_string($value) ? $value : null;
        }

        return null;
    }

    /**
     * Validates whether a string is a syntactically correct CSS class name.
     *
     * Checks if the provided class name matches the CSS class name pattern defined by {@see VALID_CSS_CLASS_PATTERN},
     * ensuring compliance with modern CSS frameworks and safe HTML rendering.
     *
     * The validation accepts the following characters.
     * - Letters (a-z, A-Z)
     * - Digits (0-9)
     * - Colons (:) for pseudo-class modifiers like `hover:` or `lg:`
     * - Dots (.) for utility notation
     * - Hyphens (-) for kebab-case naming
     * - Underscores (_) for word separation
     * - Square brackets ([]) for arbitrary value syntax like `w-[200px]`
     *
     * Empty strings and strings containing only whitespace are considered invalid.
     *
     * @param string $class CSS class name to validate.
     *
     * @return bool `true` if the class name is valid according to the pattern, or `false` otherwise.
     */
    private static function isValidClassName(string $class): bool
    {
        return preg_match(self::VALID_CSS_CLASS_PATTERN, $class) === 1;
    }

    /**
     * Normalizes an array of CSS class items into a flat array of validated string class names.
     *
     * Processes each array item that is either a `UnitEnum` or string, extracting their normalized string values and
     * validating them inline for optimal performance. Non-string values (such as int values from `BackedEnum`), invalid
     * types and invalid CSS class names are excluded.
     *
     * This method performs single-pass normalization and validation for O(n) complexity.
     *
     * @param array $classes Array of CSS class items (strings, enums, or mixed).
     *
     * @return array Flat array of validated CSS class name strings.
     *
     * @phpstan-param mixed[] $classes
     * @phpstan-return list<string>
     */
    private static function normalizeArrayClasses(array $classes): array
    {
        $normalized = [];

        foreach ($classes as $item) {
            $stringValue = self::extractStringValue($item);

            if ($stringValue !== null && self::isValidClassName($stringValue)) {
                $normalized[] = $stringValue;
            }
        }

        return $normalized;
    }

    /**
     * Normalizes a single or multiple CSS class input into a consistent validated array format.
     *
     * Converts various input types (string, array, enum, or `null`) into a normalized and validated array of CSS class
     * names suitable for HTML attribute usage. This method performs single-pass normalization and validation for
     * optimal performance.
     *
     * Note: While `BackedEnum` can have int values, CSS class names are always strings. Any int values from enums are
     * excluded during normalization.
     *
     * @param array|string|UnitEnum|null $classes Single or multiple CSS classes to normalize and validate.
     *
     * @return array Normalized and validated array of CSS class names, ready for use in HTML attributes.
     *
     * @phpstan-param mixed[]|string|UnitEnum|null $classes
     * @phpstan-return list<string>
     */
    private static function normalizeClasses(array|string|UnitEnum|null $classes): array
    {
        if ($classes === null || $classes === '') {
            return [];
        }

        if (is_string($classes)) {
            return self::splitStringClasses($classes);
        }

        if ($classes instanceof UnitEnum) {
            return self::normalizeEnumToArray($classes);
        }

        return self::normalizeArrayClasses($classes);
    }

    /**
     * Normalizes a `UnitEnum` to an array containing its validated string value.
     *
     * Extracts the scalar value from a `BackedEnum` or the name from a pure `UnitEnum` instance, validates it and
     * returns it in an array.
     *
     * If the enum has an `int` value or the string value is invalid, returns an empty array since CSS class names
     * must be valid strings.
     *
     * This method performs single-pass normalization and validation for optimal performance.
     *
     * @param UnitEnum $enum Enum instance to normalize and validate.
     *
     * @return array Array containing the enum's validated string value, or empty array if the value is int or invalid.
     *
     * @phpstan-return list<string>
     */
    private static function normalizeEnumToArray(UnitEnum $enum): array
    {
        $value = Enum::normalizeValue($enum);

        if (is_string($value) && self::isValidClassName($value)) {
            return [$value];
        }

        return [];
    }

    /**
     * Splits a space-separated string of CSS classes into a validated array.
     *
     * Splits the input string by whitespace characters, removes empty entries, and validates each class name inline for
     * optimal performance. Invalid class names are excluded from the result.
     *
     * This method performs single-pass splitting and validation for O(n) complexity.
     *
     * @param string $classes Space-separated CSS class string.
     *
     * @return array Array of validated CSS class names.
     *
     * @phpstan-return list<string>
     */
    private static function splitStringClasses(string $classes): array
    {
        $splitClasses = preg_split('/\s+/', $classes, -1, PREG_SPLIT_NO_EMPTY);

        $classParts = $splitClasses === false ? [] : $splitClasses;
        $validated = [];

        foreach ($classParts as $class) {
            if (self::isValidClassName($class)) {
                $validated[] = $class;
            }
        }

        return $validated;
    }
}
