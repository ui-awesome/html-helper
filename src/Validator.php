<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use InvalidArgumentException;

/**
 * This class provides static methods for validating various types of data.
 */
final class Validator
{
    /**
     * Validate if the value is in a valid list.
     *
     * @param string $value The value to validate.
     * @param string $exceptionMessage The exception message to throw.
     * @param string ...$compare The values in the list to compare.
     *
     * @throws InvalidArgumentException If the value is not in the list.
     */
    public static function inList(string $value, string $exceptionMessage = '', string ...$compare): void
    {
        if ($value === '') {
            throw new InvalidArgumentException(
                sprintf('The value must not be empty. The valid values are: "%s".', implode('", "', $compare))
            );
        }

        if (in_array($value, $compare, true) === false) {
            throw new InvalidArgumentException(sprintf($exceptionMessage, $value, implode('", "', $compare)));
        }
    }

    /**
     * Validate if the value is an iterable or null based on the type.
     *
     * @param mixed $value The value to validate.
     *
     * @throws InvalidArgumentException If the value is invalid.
     */
    public static function isIterable(mixed $value): void
    {
        if (!is_iterable($value) && $value !== null) {
            throw new InvalidArgumentException(
                sprintf('The value must be an iterable or null value. The value is: %s.', gettype($value))
            );
        }
    }

    /**
     * Validate if the value is numeric or null based on the type.
     *
     * @param mixed $value The value to validate.
     *
     * @throws InvalidArgumentException If the value is invalid.
     */
    public static function isNumeric(mixed $value): void
    {
        if ($value !== null && $value !== '' && !is_numeric($value)) {
            throw new InvalidArgumentException(
                sprintf('The value must be a numeric or null value. The value is: %s.', gettype($value))
            );
        }
    }

    /**
     * Validate if the value is a scalar or null based on the type.
     *
     * @param mixed ...$values The values to validate.
     *
     * @throws InvalidArgumentException If the value is invalid.
     */
    public static function isScalar(mixed ...$values): void
    {
        foreach ($values as $value) {
            if (is_scalar($value) === false && $value !== null) {
                throw new InvalidArgumentException(
                    sprintf('The value must be a scalar or null value. The value is: %s.', gettype($value))
                );
            }
        }
    }

    /**
     * Validate if the value is a string or null based on the type.
     *
     * @param mixed $value The value to validate.
     *
     * @throws InvalidArgumentException If the value is invalid.
     */
    public static function isString(mixed $value): void
    {
        if ($value !== null && !is_string($value)) {
            throw new InvalidArgumentException(
                sprintf('The value must be a string or null value. The value is: %s.', gettype($value))
            );
        }
    }
}
