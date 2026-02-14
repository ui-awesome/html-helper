<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use BackedEnum;
use InvalidArgumentException;
use Stringable;
use UIAwesome\Html\Helper\Exception\Message;
use UnitEnum;

use function array_map;
use function gettype;
use function is_array;
use function is_scalar;

/**
 * Provides reusable normalization helpers for enum and scalar values.
 *
 * {@see BackedEnum} for enums with scalar values.
 * {@see UnitEnum} for all enum types.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseEnum
{
    /**
     * Normalizes an array of enum values or mixed values to their scalar values or names.
     *
     * Applies {@see normalizeValue()} to each element.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\Enum::normalizeArray(['foo', Status::ACTIVE, 42]);
     * // ['foo', 'active', 42]
     * ```
     *
     * @param array $values Array of enum instances or mixed values to normalize.
     *
     * @throws InvalidArgumentException if any value is not an enum, scalar, `array`, or `null`.
     *
     * @return array Array of normalized scalar values, names, or original values for non-enums.
     *
     * {@see normalizeValue()} for single value normalization.
     *
     * @phpstan-param mixed[] $values
     * @phpstan-return mixed[]
     */
    public static function normalizeArray(array $values): array
    {
        return array_map(self::normalizeValue(...), $values);
    }

    /**
     * Normalizes a single enum value to its scalar value or name.
     *
     * Returns the original value unchanged for non-enum input.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\Enum::normalizeValue(Status::ACTIVE);
     * // 'active'
     * ```
     *
     * @param mixed $value Value to normalize.
     *
     * @throws InvalidArgumentException if the value is not an enum, scalar, `array`, or `null`.
     *
     * @return array|bool|float|int|string|null Scalar value for BackedEnum, name for pure enums, or the original value
     * for non-enums.
     *
     * {@see normalizeArray()} for batch normalization.
     *
     * @phpstan-return ($value is UnitEnum ? int|string : ($value is string ? string : mixed[]|bool|float|int|null))
     */
    public static function normalizeValue(mixed $value): array|bool|float|int|string|null
    {
        if ($value instanceof UnitEnum) {
            return $value instanceof BackedEnum ? $value->value : $value->name;
        }

        if ($value instanceof Stringable) {
            return (string) $value;
        }

        if (is_array($value) === false && is_scalar($value) === false && $value !== null) {
            throw new InvalidArgumentException(
                Message::VALUE_SHOULD_BE_ARRAY_SCALAR_NULL_ENUM->getMessage(gettype($value)),
            );
        }

        return $value;
    }
}
