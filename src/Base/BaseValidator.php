<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use InvalidArgumentException;
use UIAwesome\Html\Helper\Enum;
use UIAwesome\Html\Helper\Exception\Message;
use UnitEnum;

use function ctype_digit;
use function implode;
use function in_array;
use function is_int;

/**
 * Base class for advanced, type-safe validation of values in HTML helper systems.
 *
 * Provides a unified, immutable API for validating values used in HTML attributes, tag rendering, and view logic,
 * ensuring standards-compliant, predictable, and secure output for modern web applications.
 *
 * Key features.
 * - Efficient validation of allowed values for attribute rendering and tag construction.
 * - Immutable, stateless design for safe reuse in helpers and components.
 * - Integer-like value validation with strict type and range checks.
 * - Integration-ready for tag, attribute, and view rendering systems.
 * - Type-safe, static validation methods for HTML values.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseValidator
{
    /**
     * Validates whether a value is an integer or an integer-like string within a specified range.
     *
     * Ensures that the provided value is either an {@see int} or a string representing an integer, and that it falls
     * within the specified minimum and optional maximum bounds. Returns {@see true} if the value is valid, {@see false}
     * otherwise.
     *
     * This method is designed for use in HTML attribute validation, tag rendering, and view systems requiring strict
     * type and range checks for numeric values.
     *
     * @param int|string $value Value to validate as integer-like.
     * @param int|null $min Minimum allowed value (inclusive). If {@see null}, minimum is zero.
     * @param int|null $max Optional maximum allowed value (inclusive). If {@see null}, no upper bound is enforced.
     *
     * @return bool {@see true} if the value is integer-like and within bounds, {@see false} otherwise.
     *
     * Usage example:
     * ```php
     * // invalid cases
     * Validator::intLike(150, 0, 100);
     * // `false`
     * Validator::intLike('-5', 0);
     * // `false`
     * Validator::intLike('abc', 0, 100);
     * // `false`
     *
     * // valid cases
     * Validator::intLike('42', 0, 100);
     * // `true`
     * ```
     */
    public static function intLike(int|string $value, int|null $min = null, int|null $max = null): bool
    {
        $min ??= 0;

        if (is_int($value)) {
            return $value >= $min && ($max === null || $value <= $max);
        }

        if ($value === '' || $value[0] === '-' || $value[0] === '+' || ctype_digit($value) === false) {
            return false;
        }

        $intValue = (int) $value;

        return $intValue >= $min && ($max === null || $intValue <= $max);
    }

    /**
     * Validates that a value is one of the allowed options for an HTML attribute or tag argument.
     *
     * Normalizes the provided value and allowed list, and checks strict membership. If the value is empty or `null`,
     * validation passes.
     *
     * @param int|string|UnitEnum $value Value to validate.
     * @param array $allowed List of allowed values for validation.
     * @param string $argumentName Argument name for error reporting (default: 'value').
     *
     * @throws InvalidArgumentException if the value is not in the allowed list.
     *
     * @phpstan-param mixed[] $allowed
     *
     * Usage example:
     * ```php
     * // invalid case
     * Validator::oneOf('blue', ['red', 'green', 'yellow'], 'color');
     * // throws InvalidArgumentException.
     *    "Value 'blue' for argument 'color' is not in the allowed list: 'red', 'green', 'yellow'."
     *
     * // valid case
     * Validator::oneOf(Status::ACTIVE, [Status::ACTIVE, Status::INACTIVE], 'status');
     * ```
     */
    public static function oneOf(
        int|string|UnitEnum $value,
        array $allowed,
        string $argumentName = 'value',
    ): void {
        $normalizedAllowedValues = Enum::normalizeArray($allowed);
        /** @phpstan-var int|string|null $normalizedValue */
        $normalizedValue = Enum::normalizeValue($value);

        if ($normalizedValue === '' || $normalizedValue === null) {
            return;
        }

        if (in_array($normalizedValue, $normalizedAllowedValues, true)) {
            return;
        }

        throw new InvalidArgumentException(
            Message::VALUE_NOT_IN_LIST->getMessage(
                $normalizedValue,
                $argumentName,
                implode("', '", $normalizedAllowedValues),
            ),
        );
    }
}
