<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Exception;

use function sprintf;

/**
 * Represents standardized error messages.
 *
 * This enum defines formatted error messages for various error conditions that may occur during operations such as
 * value validation.
 *
 * It provides a consistent and standardized way to present error messages across the system.
 *
 * Each case represents a specific type of error, with a message template that can be populated with dynamic values
 * using the {@see Message::getMessage()} method.
 *
 * This centralized approach improves the consistency of error messages and simplifies potential internationalization.
 *
 * Key features.
 * - Centralization of an error text for easier maintenance.
 * - Consistent error handling across the system.
 * - Integration with specific exception classes.
 * - Message formatting with dynamic parameters.
 * - Standardized error messages for common and utility cases.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
enum Message: string
{
    /**
     * Error when a property cannot be parsed.
     *
     * Format: 'Property name must contain word characters only.'
     */
    case CANNOT_PARSE_PROPERTY = 'Property name must contain word characters only.';

    /**
     * Error when the form model name is empty for tabular inputs.
     *
     * Format: 'Form model name cannot be empty for tabular inputs.'
     */
    case FORM_MODEL_NAME_CANNOT_BE_EMPTY = 'Form model name cannot be empty for tabular inputs.';

    /**
     * Error when a delimiter is incorrect.
     *
     * Format: 'Incorrect delimiter.'
     */
    case INCORRET_DELIMITER = 'Incorrect delimiter.';

    /**
     * Error when a regular expression is incorrect.
     *
     * Format: 'Incorrect regular expression or malformed pattern.'
     */
    case INCORRET_REGEXP = 'Incorrect regular expression or malformed pattern.';

    /**
     * Error when the length of a regular expression is less than two.
     *
     * Format: "Length of the regular expression cannot be less than '2'."
     */
    case LENGTH_LESS_THAN_TWO = "Length of the regular expression cannot be less than '2'.";

    /**
     * Error when a value is not in the list of valid values.
     *
     * Format: "Value '%s' is not in the list of valid values for '%s': '%s'."
     */
    case VALUE_NOT_IN_LIST = "Value '%s' is not in the list of valid values for '%s': '%s'.";

    /**
     * Error when a value is of an invalid type.
     *
     * Format: "Value should be of type 'array', 'scalar', 'null', or 'enum'; '%s' given."
     */
    case VALUE_SHOULD_BE_ARRAY_SCALAR_NULL_ENUM = "Value should be of type 'array', 'scalar', 'null', or 'enum'; " .
    "'%s' given.";

    /**
     * Returns the formatted message string for the error case.
     *
     * Retrieves and formats the error message string by interpolating the provided arguments.
     *
     * @param int|string ...$argument Dynamic arguments to insert into the message.
     *
     * @return string Error message with interpolated arguments.
     *
     * Usage example:
     * ```php
     * throw new InvalidArgumentException(Message::VALUE_CANNOT_BE_EMPTY->getMessage('status', 'active, inactive'));
     * ```
     */
    public function getMessage(int|string ...$argument): string
    {
        return sprintf($this->value, ...$argument);
    }
}
