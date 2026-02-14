<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use InvalidArgumentException;
use Stringable;
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
 * Provides reusable normalization and validation for HTML `class` attributes.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseCSSClass
{
    /**
     * Regular expression for validating supported CSS class names.
     *
     * @see https://www.w3.org/TR/CSS21/syndata.html#characters CSS 2.1 Characters and case specification.
     */
    private const VALID_CSS_CLASS_PATTERN = '/^[^\s@!;<>"{}|^&*`\\\\$]+$/u';

    /**
     * Adds one or more CSS classes to an attribute array with validation and merging logic.
     *
     * Replaces the existing `class` attribute when `$override` is `true`; otherwise merges unique class names.
     *
     * Usage example:
     * ```php
     * $attributes = ['id' => 'main'];
     * \UIAwesome\Html\Helper\CSSClass::add($attributes, ['btn', 'btn-primary']);
     * // ['id' => 'main', 'class' => 'btn btn-primary']
     * ```
     *
     * @param array $attributes Attribute array to modify. Passed by reference and updated in place.
     * @param array|string|Stringable|UnitEnum|null $classes Classes to add.
     * @param bool $override Whether to override (`true`) or merge (`false`, default) with existing classes.
     *
     * @phpstan-param mixed[] $attributes
     * @phpstan-param mixed[]|string|Stringable|UnitEnum|null $classes
     */
    public static function add(
        array &$attributes,
        array|string|Stringable|UnitEnum|null $classes,
        bool $override = false,
    ): void {
        $normalizedClasses = self::normalizeClasses($classes);

        if ($normalizedClasses === []) {
            return;
        }

        if ($override) {
            $attributes['class'] = implode(' ', $normalizedClasses);
        } else {
            $existingClasses = [];

            if (
                isset($attributes['class'])
                && (
                    is_array($attributes['class'])
                    || is_string($attributes['class'])
                    || $attributes['class'] instanceof UnitEnum
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
     * Validates the value against `$allowed` and renders it with `sprintf()`.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\CSSClass::render('secondary', 'btn-%s', ['primary', 'secondary']);
     * // 'btn-secondary'
     * ```
     *
     * @param string|UnitEnum $class Class value to validate and render.
     * @param string $baseClass Base class format string (for example, `btn-%s`).
     * @param array $allowed List of allowed class values for validation.
     *
     * @throws InvalidArgumentException if the class value is not in the allowed list.
     *
     * @return string Formatted and validated CSS class name.
     *
     * {@see Enum::normalizeValue()} for enum normalization logic.
     *
     * @phpstan-param list<scalar|UnitEnum|null> $allowed
     */
    public static function render(string|UnitEnum $class, string $baseClass, array $allowed): string
    {
        $value = Enum::normalizeValue($class);

        Validator::oneOf($value, $allowed, 'class');

        return sprintf($baseClass, $value);
    }

    /**
     * Extracts a string value from a UnitEnum or string item.
     *
     * @param mixed $item Item to extract string value from (UnitEnum, `string`, or other).
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
     * Validates a CSS class name against {@see VALID_CSS_CLASS_PATTERN}.
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
     * @param array|string|Stringable|UnitEnum|null $classes Single or multiple CSS classes to normalize and validate.
     *
     * @return array Normalized and validated array of CSS class names, ready for use in HTML attributes.
     *
     * @phpstan-param mixed[]|string|Stringable|UnitEnum|null $classes
     * @phpstan-return list<string>
     */
    private static function normalizeClasses(array|string|Stringable|UnitEnum|null $classes): array
    {
        if ($classes === null || $classes === '') {
            return [];
        }

        if (is_string($classes) || $classes instanceof Stringable) {
            return self::splitStringClasses($classes);
        }

        if ($classes instanceof UnitEnum) {
            return self::normalizeEnumToArray($classes);
        }

        return self::normalizeArrayClasses($classes);
    }

    /**
     * Normalizes a UnitEnum to an array containing its validated string value.
     *
     * @param UnitEnum $enum Enum instance to normalize and validate.
     *
     * @return array Array containing the enum's validated `string` value, or empty `array` if the value is `int` or
     * invalid.
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
     * @param string|Stringable $classes Space-separated CSS class string.
     *
     * @return array Array of validated CSS class names.
     *
     * @phpstan-return list<string>
     */
    private static function splitStringClasses(string|Stringable $classes): array
    {
        $splitClasses = preg_split('/\s+/', (string) $classes, -1, PREG_SPLIT_NO_EMPTY);

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
