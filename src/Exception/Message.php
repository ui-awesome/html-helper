<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Exception;

use function sprintf;

/**
 * Represents error message templates.
 *
 * This enum defines formatted error messages for various error conditions that may occur during operations such as
 * parsing properties, validating input values, and handling form models.
 *
 * It provides message templates that can be formatted at call sites.
 *
 * Each case represents a specific type of error, with a message template that can be populated with dynamic values
 * using the {@see Message::getMessage()} method.
 *
 * Each message template can be formatted with arguments.
 *
 * Key features.
 * - Can be used by exception call sites that need formatted messages.
 * - Defines message templates as enum cases.
 * - Formats templates with `sprintf()` via {@see Message::getMessage()}.
 * - Supports message formatting with dynamic parameters.
 * - Uses the enum case `value` as the template string.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
enum Message: string
{
    /**
     * Error when a property cannot be parsed.
     *
     * Format: "Property name '%s' must contain word characters only."
     */
    case CANNOT_PARSE_PROPERTY = "Property name '%s' must contain word characters only.";

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
    case INCORRECT_DELIMITER = 'Incorrect delimiter.';

    /**
     * Error when a regular expression is incorrect.
     *
     * Format: 'Incorrect regular expression or malformed pattern.'
     */
    case INCORRECT_REGEXP = 'Incorrect regular expression or malformed pattern.';

    /**
     * Error when a key is not a non-empty string.
     *
     * Format: "Key must be a non-empty string."
     */
    case KEY_MUST_BE_NON_EMPTY_STRING = 'Key must be a non-empty string.';

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
    case VALUE_SHOULD_BE_ARRAY_SCALAR_NULL_ENUM = "Value should be of type 'array', 'scalar', 'null', or 'enum'; "
    . "'%s' given.";

    /**
     * Returns the formatted message string for the error case.
     *
     * @param int|string ...$argument Values to insert into the message template.
     *
     * @return string Formatted error message with interpolated arguments.
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
