<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use InvalidArgumentException;
use Stringable;
use UIAwesome\Html\Helper\Enum;
use UIAwesome\Html\Helper\Exception\Message;
use UnitEnum;

use function ctype_digit;
use function implode;
use function in_array;
use function is_float;
use function is_int;
use function is_numeric;
use function is_string;
use function max;
use function str_ends_with;
use function stripos;
use function substr;

/**
 * Base class for validation utilities used by HTML helper components.
 *
 * Provides a unified API for validating common values used in HTML attribute rendering and helper configuration,
 * supporting integer-like inputs and allow-list membership checks.
 *
 * Key features.
 * - Allow-list validation with UnitEnum normalization for consistent, strict comparisons.
 * - Integer-like validation for int and integer strings with optional range constraints.
 * - Offset-like validation for percentage strings and ratio values.
 * - Positive-like number validation for int, float, and numeric strings with optional max constraint.
 * - Predictable behavior with explicit exceptions for invalid values.
 *
 * {@see Enum} for enum normalization during allow-list validation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseValidator
{
    /**
     * Validates whether a value is an integer or an integer-like string within a specified range.
     *
     * Ensures that the provided value is either an int or a string representing an integer, and that it falls within
     * the specified minimum and optional maximum bounds. Returns `true` if the value is valid, `false` otherwise.
     *
     * This method is designed for use in HTML attribute validation, tag rendering, and view systems requiring strict
     * type and range checks for numeric values.
     *
     * @param int|string|Stringable $value Value to validate as integer-like.
     * @param int|null $min Minimum allowed value (inclusive). If `null`, minimum is zero.
     * @param int|null $max Optional maximum allowed value (inclusive). If `null`, no upper bound is enforced.
     *
     * @return bool `true` if the value is integer-like and within bounds, `false` otherwise.
     *
     * Usage example:
     * ```php
     * // invalid cases
     * Validator::intLike(150, 0, 100);
     * // `false`
     *
     * Validator::intLike('-5', 0);
     * // `false`
     *
     * Validator::intLike('abc', 0, 100);
     * // `false`
     *
     * // valid cases
     * Validator::intLike('42', 0, 100);
     * // `true`
     *
     * Validator::intLike(
     *    new class implements Stringable {
     *        public function __toString(): string
     *        {
     *            return '42';
     *        }
     *    }
     * );
     * // `true`
     * ```
     */
    public static function intLike(int|string|Stringable $value, int|null $min = null, int|null $max = null): bool
    {
        $min ??= 0;

        if (is_int($value)) {
            return $value >= $min && ($max === null || $value <= $max);
        }

        if ($value instanceof Stringable) {
            $value = (string) $value;
        }

        if ($value[0] === '+') {
            $value = substr($value, 1);
        }

        if ($value === '' || $value[0] === '-' || ctype_digit($value) === false) {
            return false;
        }

        return $value >= $min && ($max === null || $value <= $max);
    }

    /**
     * Validates whether a value represents an offset value.
     *
     * If the value is a string that ends with `%`, the numeric part is validated as a non-negative number not greater
     * than `100`. Otherwise, the value is validated as a non-negative number not greater than `1`.
     *
     * @param float|int|string|Stringable $value Value to validate.
     *
     * @return bool `true` if the value matches the accepted format and bounds, `false` otherwise.
     *
     * Usage example:
     * ```php
     * // ratio values (0-1)
     * Validator::offsetLike(0.5);
     * // `true`
     *
     * Validator::offsetLike('1.2');
     * // `false`
     *
     * // percent values (0%-100%)
     * Validator::offsetLike('50%');
     * // `true`
     *
     * Validator::offsetLike('150%');
     * // `false`
     * ```
     */
    public static function offsetLike(float|int|string|Stringable $value): bool
    {
        if (is_string($value) && str_ends_with($value, '%')) {
            $percentValue = substr($value, 0, -1);

            return self::positiveLike($percentValue, max: 100);
        }

        return self::positiveLike($value, max: 1);
    }

    /**
     * Validates that a value is one of the allowed options for an HTML attribute or tag argument.
     *
     * Normalizes the provided value and allowed list, and checks strict membership. If the value is empty or `null`,
     * validation passes.
     *
     * @param int|string|Stringable|UnitEnum|null $value Value to validate.
     * @param array $allowed List of allowed values for validation.
     * @param string|UnitEnum $argumentName Argument name for error reporting (default: 'value').
     *
     * @throws InvalidArgumentException if the value is not in the allowed list.
     *
     * @phpstan-param list<scalar|UnitEnum|null> $allowed
     *
     * Usage example:
     * ```php
     * // invalid case
     * Validator::oneOf('blue', ['red', 'green', 'yellow'], 'color');
     * // throws InvalidArgumentException.
     * // "Value 'blue' for argument 'color' is not in the allowed list: 'red', 'green', 'yellow'."
     *
     * // valid case
     * Validator::oneOf(Status::ACTIVE, [Status::ACTIVE, Status::INACTIVE], 'status');
     *
     * Validator::oneOf(
     *     new class implements Stringable {
     *         public function __toString(): string
     *         {
     *             return 'admin';
     *         }
     *     },
     *     ['admin', 'editor', 'viewer'],
     *     'role',
     * );
     * ```
     */
    public static function oneOf(
        int|string|Stringable|UnitEnum|null $value,
        array $allowed,
        string|UnitEnum $argumentName = 'value',
    ): void {
        $normalizedAllowedValues = Enum::normalizeArray($allowed);
        /** @phpstan-var int|string|null $normalizedValue */
        $normalizedValue = Enum::normalizeValue($value);
        $normalizedArgumentName = Enum::normalizeValue($argumentName);

        if ($normalizedValue === '' || $normalizedValue === null) {
            return;
        }

        if (in_array($normalizedValue, $normalizedAllowedValues, true)) {
            return;
        }

        throw new InvalidArgumentException(
            Message::VALUE_NOT_IN_LIST->getMessage(
                $normalizedValue,
                $normalizedArgumentName,
                implode("', '", $normalizedAllowedValues),
            ),
        );
    }

    /**
     * Validates whether a value is a positive numeric value within a specified range.
     *
     * Ensures that the provided value is an int, float, or a string/Stringable representing a non-negative numeric
     * value, and that it falls within the specified minimum and maximum bounds (both inclusive).
     *
     * This method is designed for use in HTML attribute validation where numeric ranges must be non-negative, such as
     * `stroke-miterlimit` (0.0-1.0), `opacity` (0.0-1.0), `width`, `height`, and other dimensional or fractional
     *  attributes.
     *
     * @param float|int|string|Stringable $value Value to validate as positive numeric.
     * @param float|null $min Minimum allowed value (inclusive). Defaults to `0.0`. If provided and less than `0.0`, it
     * is forced to `0.0`. The value must be greater than or equal to this bound.
     * @param float|null $max Optional maximum allowed value (inclusive). If `null`, no upper bound is enforced.
     *
     * @return bool `true` if the value is non-negative numeric and within bounds, `false` otherwise.
     *
     * Usage example:
     * ```php
     * // negative values not allowed
     * Validator::positiveLike(-1);
     * // `false`
     *
     * // not numeric
     * Validator::positiveLike('abc');
     * // `false`
     *
     * // above maximum of 1.0
     * Validator::positiveLike(1.5, 0.0, 1.0);
     * // `false`
     *
     * // min is forced to 0.0, value is negative
     * Validator::positiveLike(-0.5, -10.0, 1.0);
     * // `false`
     *
     * // zero is valid, inclusive minimum
     * Validator::positiveLike(0);
     * // `true`
     *
     * // within '0.0-1.0' range, like stroke-miterlimit
     * Validator::positiveLike(0.5, 0.0, 1.0);
     * // `true`
     *
     * // maximum boundary, inclusive
     * Validator::positiveLike('1.0', 0.0, 1.0);
     * // `true`
     *
     * // no maximum constraint
     * Validator::positiveLike('2.5');
     * // `true`
     *
     * // within '0.0-1.0' range using Stringable
     * Validator::positiveLike(
     *    new class implements Stringable {
     *        public function __toString(): string
     *        {
     *            return '0.75';
     *        }
     *    },
     *    0.0,
     *    1.0
     * );
     * // `true`
     * ```
     */
    public static function positiveLike(
        float|int|string|Stringable $value,
        float|null $min = null,
        float|null $max = null,
    ): bool {
        $min = max($min ?? 0.0, 0.0);

        if (is_int($value) || is_float($value)) {
            return ($value >= $min) && ($max === null || $value <= $max);
        }

        if ($value instanceof Stringable) {
            $value = (string) $value;
        }

        if (is_numeric($value) === false) {
            return false;
        }

        if ($value[0] === '-' || $value[0] === ' ' || $value[-1] === ' ') {
            return false;
        }

        if (stripos($value, 'e') !== false) {
            return false;
        }

        return $value >= $min && ($max === null || $value <= $max);
    }
}
