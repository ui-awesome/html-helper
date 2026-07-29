<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Provider;

use UIAwesome\Html\Helper\Exception\Message;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\MessageTest} test cases.
 */
final class MessageProvider
{
    /**
     * @return array<string, array{array<string, string>}>
     */
    public static function contract(): array
    {
        return [
            'public surface' => [
                [
                    'CANNOT_PARSE_PROPERTY' => "Property name '%s' must contain word characters only.",
                    'FORM_MODEL_NAME_CANNOT_BE_EMPTY' => 'Form model name cannot be empty for tabular inputs.',
                    'INCORRECT_DELIMITER' => 'Incorrect delimiter.',
                    'INCORRECT_REGEXP' => 'Incorrect regular expression or malformed pattern.',
                    'KEY_MUST_BE_NON_EMPTY_STRING' => 'Key must be a non-empty string.',
                    'LENGTH_LESS_THAN_TWO' => "Length of the regular expression cannot be less than '2'.",
                    'VALUE_NOT_IN_LIST' => "Value '%s' is not in the list of valid values for '%s': '%s'.",
                    'VALUE_SHOULD_BE_ARRAY_SCALAR_NULL_ENUM' => "Value should be of type 'array', 'scalar', 'null', "
                        . "or 'enum'; '%s' given.",
                ],
            ],
        ];
    }

    /**
     * @return array<string, array{Message, list<int|string>, string}>
     */
    public static function getMessage(): array
    {
        return [
            'multiple placeholders' => [
                Message::VALUE_NOT_IN_LIST,
                ['blue', 'color', "red', 'green"],
                "Value 'blue' is not in the list of valid values for 'color': 'red', 'green'.",
            ],
            'no placeholders' => [
                Message::INCORRECT_DELIMITER,
                [],
                'Incorrect delimiter.',
            ],
            'single placeholder' => [
                Message::CANNOT_PARSE_PROPERTY,
                ['user-name'],
                "Property name 'user-name' must contain word characters only.",
            ],
            'unsupported type placeholder' => [
                Message::VALUE_SHOULD_BE_ARRAY_SCALAR_NULL_ENUM,
                ['object'],
                "Value should be of type 'array', 'scalar', 'null', or 'enum'; 'object' given.",
            ],
        ];
    }
}
