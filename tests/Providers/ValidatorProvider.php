<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

use Stringable;
use UIAwesome\Html\Helper\Enum;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Support\Stub\Enum\{Priority, Status, Theme};
use UnitEnum;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\ValidatorTest} class.
 *
 * Supplies focused datasets used by validation helpers for integer-like checks and allowed-value lists.
 *
 * The cases cover numeric string handling, boundary conditions, enum comparisons, mixed-type lists, and failure message
 * generation for invalid inputs.
 *
 * Key features.
 * - Provide comprehensive `oneOf` datasets including backed enums, unit enums and mixed-type lists.
 * - Return tuples describing input, constraints, expected validity and expected message text.
 * - Validate integer-like string and numeric inputs with `min`/`max` boundaries.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class ValidatorProvider
{
    /**
     * Provides datasets for integer-like validation.
     *
     * Each dataset returns a tuple: value, minimum, maximum, expected validity, and an expected message. Cases include
     * integers, numeric strings, leading zeroes, floats, scientific notation, whitespace and sign edge cases to ensure
     * deterministic validation behaviour.
     *
     * @return array Test data for int-like validation.
     *
     * @phpstan-return array<string, array{int|string|Stringable, int|null, int|null, bool, string}>
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
            'stringable' => [
                new class {
                    public function __toString(): string
                    {
                        return '5';
                    }
                },
                0,
                10,
                true,
                'Should be valid value.',
            ],
        ];
    }

    /**
     * Provides datasets for `oneOf` validation checks.
     *
     * Each dataset returns: attribute name, tested value, allowed list, a strict comparison flag, and the expected
     * message. Datasets cover backed enums, unit enums, mixed enum lists, `null`, scalar comparisons, and message
     * generation for failure scenarios.
     *
     * @return array Test data for oneOf validation.
     *
     * @phpstan-return array<string, array{string|UnitEnum, mixed, list<mixed>, bool, string}>
     */
    public static function oneOf(): array
    {
        return [
            'backed enum argument name' => [
                Status::ACTIVE,
                Status::INACTIVE,
                Status::cases(),
                false,
                '',
            ],
            'backed enum argument name not in list' => [
                Status::ACTIVE,
                'invalid_value',
                Status::cases(),
                true,
                Message::VALUE_NOT_IN_LIST->getMessage(
                    'invalid_value',
                    Status::ACTIVE->value,
                    implode('\', \'', Enum::normalizeArray(Status::cases())),
                ),
            ],
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
                Message::VALUE_NOT_IN_LIST->getMessage(
                    'a',
                    'attribute',
                    '',
                ),
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
            'null value' => [
                'attribute',
                null,
                [
                    'a',
                    'b',
                    'c',
                ],
                false,
                '',
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
            'stringable' => [
                'attribute',
                new class {
                    public function __toString(): string
                    {
                        return 'b';
                    }
                },
                [
                    'a',
                    'b',
                    'c',
                ],
                false,
                '',
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

    /**
     * Provides datasets for positive-like validation.
     *
     * Each dataset returns a tuple: value, maximum, expected validity, and an expected message. Cases include positive
     * integers, positive floats, positive numeric strings, zero and negative values, non-numeric strings, and boundary
     * conditions with maximum constraints.
     *
     * @return array Test data for positive-like validation.
     *
     * @phpstan-return array<string, array{int|float|string|Stringable, float|null, bool, string}>
     */
    public static function positiveLike(): array
    {
        return [
            'float equal max' => [
                10.0,
                10,
                true,
                'Should be valid value.',
            ],
            'float negative invalid' => [
                -1.5,
                null,
                false,
                'Should be invalid value.',
            ],
            'float positive above max' => [
                15.5,
                10,
                false,
                'Should be invalid value.',
            ],
            'float positive valid' => [
                1.5,
                null,
                true,
                'Should be valid value.',
            ],
            'float positive within max' => [
                2.5,
                10,
                true,
                'Should be valid value.',
            ],
            'float zero invalid' => [
                0.0,
                null,
                false,
                'Should be invalid value.',
            ],
            'integer equal max' => [
                10,
                10,
                true,
                'Should be valid value.',
            ],
            'integer negative invalid' => [
                -1,
                null,
                false,
                'Should be invalid value.',
            ],
            'integer positive above max' => [
                15,
                10,
                false,
                'Should be invalid value.',
            ],
            'integer positive valid' => [
                1,
                null,
                true,
                'Should be valid value.',
            ],
            'integer positive within max' => [
                5,
                10,
                true,
                'Should be valid value.',
            ],
            'integer zero invalid' => [
                0,
                null,
                false,
                'Should be invalid value.',
            ],
            'string decimal equal max' => [
                '10.0',
                10,
                true,
                'Should be valid value.',
            ],
            'string decimal positive above max' => [
                '15.5',
                10,
                false,
                'Should be invalid value.',
            ],
            'string decimal positive valid' => [
                '1.5',
                null,
                true,
                'Should be valid value.',
            ],
            'string decimal positive within max' => [
                '2.5',
                10,
                true,
                'Should be valid value.',
            ],
            'string equal max' => [
                '10',
                10,
                true,
                'Should be valid value.',
            ],
            'string integer positive above max' => [
                '15',
                10,
                false,
                'Should be invalid value.',
            ],
            'string integer positive valid' => [
                '1',
                null,
                true,
                'Should be valid value.',
            ],
            'string integer positive within max' => [
                '5',
                10,
                true,
                'Should be valid value.',
            ],
            'string negative float invalid' => [
                '-1.5',
                null,
                false,
                'Should be invalid value.',
            ],
            'string negative invalid' => [
                '-1',
                null,
                false,
                'Should be invalid value.',
            ],
            'string non numeric invalid' => [
                'abc',
                null,
                false,
                'Should be invalid value.',
            ],
            'string scientific notation invalid' => [
                '1e3',
                null,
                false,
                'Should be invalid value.',
            ],
            'string with leading space invalid' => [
                ' 1.5',
                null,
                false,
                'Should be invalid value.',
            ],
            'string with plus sign float invalid' => [
                '+1.5',
                null,
                false,
                'Should be invalid value.',
            ],
            'string with plus sign invalid' => [
                '+1',
                null,
                false,
                'Should be invalid value.',
            ],
            'string with trailing space invalid' => [
                '1.5 ',
                null,
                false,
                'Should be invalid value.',
            ],
            'string zero float invalid' => [
                '0.0',
                null,
                false,
                'Should be invalid value.',
            ],
            'string zero invalid' => [
                '0',
                null,
                false,
                'Should be invalid value.',
            ],
            'string zero negative float invalid' => [
                '-0.0',
                null,
                false,
                'Should be invalid value.',
            ],
            'string zero negative invalid' => [
                '-0',
                null,
                false,
                'Should be invalid value.',
            ],
            'stringable negative invalid' => [
                new class {
                    public function __toString(): string
                    {
                        return '-1.5';
                    }
                },
                null,
                false,
                'Should be invalid value.',
            ],
            'stringable positive valid' => [
                new class {
                    public function __toString(): string
                    {
                        return '3.5';
                    }
                },
                null,
                true,
                'Should be valid value.',
            ],
            'stringable positive within max' => [
                new class {
                    public function __toString(): string
                    {
                        return '5.5';
                    }
                },
                10,
                true,
                'Should be valid value.',
            ],
            'stringable zero invalid' => [
                new class {
                    public function __toString(): string
                    {
                        return '0';
                    }
                },
                null,
                false,
                'Should be invalid value.',
            ],
        ];
    }
}
