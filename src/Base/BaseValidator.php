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
use function stripos;

/**
 * Base class for validation utilities used by HTML helper components.
 *
 * Provides a unified API for validating common values used in HTML attribute rendering and helper configuration,
 * supporting integer-like inputs and allow-list membership checks.
 *
 * Key features.
 * - Allow-list validation with UnitEnum normalization for consistent, strict comparisons.
 * - Integer-like validation for int and integer strings with optional range constraints.
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

        if ($value[0] === '-' || $value[0] === '+' || ctype_digit($value) === false) {
            return false;
        }

        return $value >= $min && ($max === null || $value <= $max);
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
     * Validates whether a value is a positive number greater than zero.
     *
     * Ensures that the provided value is an int, float, or a string/Stringable representing a positive number greater
     * than zero, and that it falls within the specified maximum bound if provided.
     *
     * This method is designed for use in HTML attribute validation where zero or negative values are semantically
     * incorrect, such as `width`, `height`, `spacing`, and other dimensional attributes.
     *
     * @param int|float|string|Stringable $value Value to validate as positive.
     * @param float|null $max Optional maximum allowed value (inclusive). If `null`, no upper bound is enforced.
     *
     * @return bool `true` if the value is positive and within bounds, `false` otherwise.
     *
     * Usage example:
     * ```php
     * // invalid cases
     * Validator::positiveLike(0);
     * // `false`
     *
     * Validator::positiveLike(-1);
     * // `false`
     *
     * Validator::positiveLike('abc');
     * // `false`
     *
     * // valid cases
     * Validator::positiveLike(1);
     * // `true`
     *
     * Validator::positiveLike('2.5');
     * // `true`
     *
     * Validator::positiveLike(
     *    new class implements Stringable {
     *        public function __toString(): string
     *        {
     *            return '3.5';
     *        }
     *    }
     * );
     * // `true`
     * ```
     */
    public static function positiveLike(int|float|string|Stringable $value, float|null $max = null): bool
    {
        if (is_int($value) || is_float($value)) {
            return $value > 0.0 && ($max === null || $value <= $max);
        }

        if ($value instanceof Stringable) {
            $value = (string) $value;
        }

        if (is_numeric($value) === false) {
            return false;
        }

        if ($value[0] === '-' || $value[0] === '+' || $value[0] === ' ' || $value[-1] === ' ') {
            return false;
        }

        if (stripos($value, 'e') !== false) {
            return false;
        }

        return $value > 0.0 && ($max === null || $value <= $max);
    }
}
