<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Exception;

use function sprintf;

/**
 * Represents error message templates for attribute exceptions.
 *
 * Use {@see Message::getMessage()} to format the template with `sprintf()` arguments.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
enum Message: string
{
    /**
     * Indicates that a property expression cannot be parsed.
     */
    case CANNOT_PARSE_PROPERTY = "Property name '%s' must contain word characters only.";

    /**
     * Indicates that the form model name is missing for tabular input.
     */
    case FORM_MODEL_NAME_CANNOT_BE_EMPTY = 'Form model name cannot be empty for tabular inputs.';

    /**
     * Indicates an invalid delimiter.
     */
    case INCORRECT_DELIMITER = 'Incorrect delimiter.';

    /**
     * Indicates an invalid regular expression literal.
     */
    case INCORRECT_REGEXP = 'Incorrect regular expression or malformed pattern.';

    /**
     * Indicates that a key is not a non-empty `string`.
     */
    case KEY_MUST_BE_NON_EMPTY_STRING = 'Key must be a non-empty string.';

    /**
     * Indicates that the regular expression literal is too short.
     */
    case LENGTH_LESS_THAN_TWO = "Length of the regular expression cannot be less than '2'.";

    /**
     * Indicates that a value is outside the allowed list.
     */
    case VALUE_NOT_IN_LIST = "Value '%s' is not in the list of valid values for '%s': '%s'.";

    /**
     * Indicates that a value type is unsupported.
     */
    case VALUE_SHOULD_BE_ARRAY_SCALAR_NULL_ENUM = "Value should be of type 'array', 'scalar', 'null', or 'enum'; "
    . "'%s' given.";

    /**
     * Returns the formatted message string for the error case.
     *
     * Usage example:
     * ```php
     * throw new InvalidArgumentException(
     *     \UIAwesome\Html\Helper\Exception\Message::VALUE_NOT_IN_LIST->getMessage('blue', 'color', 'red, green'),
     * );
     * ```
     *
     * @param int|string ...$argument Values to insert into the message template.
     *
     * @return string Formatted error message with interpolated arguments.
     */
    public function getMessage(int|string ...$argument): string
    {
        return sprintf($this->value, ...$argument);
    }
}
