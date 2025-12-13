<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

use UIAwesome\Html\Helper\Enum;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Support\Stub\Enum\{Priority, Status, Theme};

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\ValidatorTest} class.
 *
 * Supplies comprehensive test data for validating integer-like value normalization, boundary checks, and list
 * membership for HTML attribute scenarios. Ensures standards-compliant conversion, type safety, and robust validation
 * logic for attribute assignment in HTML rendering contexts.
 *
 * The test data covers real-world scenarios for integer and string input validation, including boundary conditions,
 * negative and positive values, string representations, invalid formats, and enum comparisons. It supports both scalar
 * and enum values, maintaining consistent and type-safe representation across different rendering configurations.
 *
 * The provider organizes test cases with descriptive names for clear identification of failure cases during test
 * execution and debugging sessions.
 *
 * Key features.
 * - Ensures correct normalization and validation of integer-like and string values for HTML attributes.
 * - Named test data sets for precise failure identification.
 * - Validation of boundary checks, type strictness, and mixed enum/scalar list membership.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class ValidatorProvider
{
    /**
     * Provides test cases for integer-like value validation scenarios.
     *
     * Supplies comprehensive test data for validating integer and string input values, including boundary checks,
     * negative and positive values, string representations, and invalid formats, ensuring correct normalization and
     * validation for HTML attribute usage.
     *
     * Each test case includes the input value, minimum and maximum boundaries, expected boolean result, and an
     * assertion message for clear failure identification.
     *
     * @return array Test data for integer-like value validation scenarios.
     *
     * @phpstan-return array<string, array{int|string, int|null, int|null, bool, string}>
     */
    public static function intLike(): array
    {
        return [
            'integer above max' => [
                11,
                0,
                10,
                false,
                'Should be invalid value.',
            ],
            'integer below min' => [
                -2,
                -1,
                null,
                false,
                'Should be invalid value.',
            ],
            'integer equal min' => [
                0,
                0,
                null,
                true,
                'Should be valid value.',
            ],
            'integer max boundary equal' => [
                10,
                0,
                10,
                true,
                'Should be valid value.',
            ],
            'integer min equal null' => [
                0,
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'integer negative min equal null' => [
                -1,
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'integer valid above min' => [
                5,
                0,
                null,
                true,
                'Should be valid value.',
            ],
            'integer within range' => [
                5,
                1,
                10,
                true,
                'Should be valid value.',
            ],
            'string above max' => [
                '11',
                0,
                10,
                false,
                'Should be invalid value.',
            ],
            'string below min but below max' => [
                '0',
                5,
                10,
                false,
                'Should be invalid value.',
            ],
            'string equal max' => [
                '10',
                0,
                10,
                true,
                'Should be valid value.',
            ],
            'string equal min' => [
                '5',
                5,
                null,
                true,
                'Should be valid value.',
            ],
            'string equal with min and max' => [
                '5',
                5,
                10,
                true,
                'Should be valid value.',
            ],
            'string float' => [
                '3.5',
                0,
                null,
                false,
                'Should be invalid value.',
            ],
            'string in range' => [
                '5',
                0,
                10,
                true,
                'Should be valid value.',
            ],
            'string leading zero equal min' => [
                '05',
                5,
                null,
                true,
                'Should be valid value.',
            ],
            'string min equal null' => [
                '5',
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'string negative min equal null' => [
                '-1',
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'string negative not allowed when min >= 0' => [
                '-1',
                0,
                null,
                false,
                'Should be invalid value.',
            ],
            'string non digit' => [
                'abc',
                0,
                null,
                false,
                'Should be invalid value.',
            ],
            'string numeric equals min' => [
                '0',
                0,
                null,
                true,
                'Should be valid value.',
            ],
            'string numeric valid' => [
                '5',
                0,
                null,
                true,
                'Should be valid value.',
            ],
            'string numeric with plus sign' => [
                '+1',
                0,
                null,
                false,
                'Should be invalid value.',
            ],
            'string numeric within range' => [
                '3',
                1,
                5,
                true,
                'Should be valid value.',
            ],
            'string scientific notation' => [
                '1e3',
                0,
                null,
                false,
                'Should be invalid value.',
            ],
            'string with spaces' => [
                ' 3 ',
                0,
                null,
                false,
                'Should be invalid value.',
            ],
            'string zero min equal null' => [
                '0',
                null,
                null,
                true,
                'Should be valid value.',
            ],
        ];
    }

    /**
     * Provides test cases for list membership validation scenarios.
     *
     * Supplies test data for validating whether a value is present in a list of allowed values, supporting mixed types
     * including enums and scalars. Ensures correct comparison logic, type strictness, and error messaging for HTML
     * attribute assignment.
     *
     * Each test case includes the attribute name, input value, allowed list, expected boolean result, and an assertion
     * message for clear identification.
     *
     * @return array Test data for list membership validation scenarios.
     *
     * @phpstan-return array<string, array{string, mixed, list<mixed>, bool, string}>
     */
    public static function oneOf(): array
    {
        return [
            'backed enum value in list' => [
                'attribute',
                Status::ACTIVE,
                Status::cases(),
                false,
                '',
            ],
            'empty allowed list' => [
                'attribute',
                'a',
                [],
                true,
                Message::VALUE_NOT_IN_LIST->getMessage('a', 'attribute', ''),
            ],
            'empty value-not-in-list' => [
                'attribute',
                '',
                [
                    'a',
                    'b',
                    'c',
                ],
                false,
                '',
            ],
            'invalid enum comparison' => [
                'attribute',
                Status::ACTIVE,
                Theme::cases(),
                true,
                Message::VALUE_NOT_IN_LIST->getMessage(
                    Status::ACTIVE->value,
                    'attribute',
                    implode('\', \'', Enum::normalizeArray(Theme::cases())),
                ),
            ],
            'mixed enum types backed enum value found' => [
                'attribute',
                'DARK',
                [
                    Status::ACTIVE,
                    Theme::DARK,
                    Priority::LOW,
                ],
                false,
                '',
            ],
            'mixed enum types enum instance found' => [
                'attribute',
                Status::ACTIVE,
                [
                    Status::ACTIVE,
                    Theme::DARK,
                    Priority::LOW,
                ],
                false,
                '',
            ],
            'mixed enum types int value found' => [
                'attribute',
                1,
                [
                    Status::ACTIVE,
                    Theme::DARK,
                    Priority::LOW,
                ],
                false,
                '',
            ],
            'mixed enum types string not found type strictness' => [
                'attribute',
                '1',
                [
                    Status::ACTIVE,
                    Theme::DARK,
                    Priority::LOW,
                ],
                true,
                Message::VALUE_NOT_IN_LIST->getMessage(
                    '1',
                    'attribute',
                    implode('\', \'', Enum::normalizeArray([Status::ACTIVE, Theme::DARK, Priority::LOW])),
                ),
            ],
            'string case sensitive enum value' => [
                'attribute',
                'ACTIVE',
                [
                    Status::ACTIVE,
                    Status::INACTIVE,
                ],
                true,
                Message::VALUE_NOT_IN_LIST->getMessage(
                    'ACTIVE',
                    'attribute',
                    implode('\', \'', Enum::normalizeArray([Status::ACTIVE, Status::INACTIVE])),
                ),
            ],
            'string value in list' => [
                'attribute',
                'a',
                [
                    'a',
                    'b',
                    'c',
                ],
                false,
                '',
            ],
            'string value not in list' => [
                'attribute',
                '1',
                [
                    'a',
                    'b',
                    'c',
                ],
                true,
                Message::VALUE_NOT_IN_LIST->getMessage(
                    '1',
                    'attribute',
                    implode('\', \'', Enum::normalizeArray(['a', 'b', 'c'])),
                ),
            ],
            'unit enum value in list' => [
                'attribute',
                Theme::DARK,
                Theme::cases(),
                false,
                '',
            ],
        ];
    }
}
