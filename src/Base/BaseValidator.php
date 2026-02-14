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
 * Provides reusable validation helpers for numeric and allow-list checks.
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
     * Returns `true` only for non-empty integer input within bounds.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\Validator::intLike('42', 0, 100);
     * // `true`
     * ```
     *
     * @param int|string|Stringable $value Value to validate as integer-like.
     * @param int|null $min Minimum allowed value (inclusive). If `null`, minimum is zero.
     * @param int|null $max Optional maximum allowed value (inclusive). If `null`, no upper bound is enforced.
     *
     * @return bool `true` if the value is integer-like and within bounds, `false` otherwise.
     */
    public static function intLike(int|string|Stringable $value, int|null $min = null, int|null $max = null): bool
    {
        $min ??= 0;

        if ($value === '') {
            return false;
        }

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
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\Validator::offsetLike('50%');
     * // `true`
     * ```
     *
     * @param float|int|string|Stringable $value Value to validate.
     *
     * @return bool `true` if the value matches the accepted format and bounds, `false` otherwise.
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
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\Validator::oneOf('admin', ['admin', 'editor', 'viewer'], 'role');
     * ```
     *
     * @param int|string|Stringable|UnitEnum|null $value Value to validate.
     * @param array $allowed List of allowed values for validation.
     * @param string|UnitEnum $argumentName Argument name for error reporting (default: 'value').
     *
     * @throws InvalidArgumentException if the value is not in the allowed list.
     *
     * @phpstan-param list<scalar|UnitEnum|null> $allowed
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
     * Returns `true` only for non-negative numeric input within bounds.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\Validator::positiveLike('1.0', 0.0, 1.0);
     * // `true`
     * ```
     *
     * @param float|int|string|Stringable $value Value to validate as positive numeric.
     * @param float|null $min Minimum allowed value (inclusive). Defaults to `0.0`. If provided and less than `0.0`, it
     * is forced to `0.0`. The value must be greater than or equal to this bound.
     * @param float|null $max Optional maximum allowed value (inclusive). If `null`, no upper bound is enforced.
     *
     * @return bool `true` if the value is non-negative numeric and within bounds, `false` otherwise.
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
