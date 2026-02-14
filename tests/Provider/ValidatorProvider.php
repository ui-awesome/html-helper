<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Provider;

use PHPForge\Support\Stub\{BackedInteger, BackedString, Unit};
use Stringable;
use UIAwesome\Html\Helper\Enum;
use UIAwesome\Html\Helper\Exception\Message;
use UnitEnum;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\ValidatorTest} test cases.
 *
 * Provides representative input/output pairs for validator helper methods.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class ValidatorProvider
{
    /**
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
            'string empty invalid' => [
                '',
                0,
                null,
                false,
                'Should be invalid value.',
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
                true,
                'Should be valid value.',
            ],
            'string numeric within range' => [
                '3',
                1,
                5,
                true,
                'Should be valid value.',
            ],
            'string only plus sign' => [
                '+',
                0,
                null,
                false,
                'Should be invalid value.',
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
     * @phpstan-return array<string, array{int|float|string|Stringable, bool, string}>
     */
    public static function offsetLike(): array
    {
        return [
            'float above max invalid' => [
                1.1,
                false,
                'Should be invalid offset value.',
            ],
            'float middle valid' => [
                0.5,
                true,
                'Should be valid offset value.',
            ],
            'float negative invalid' => [
                -0.1,
                false,
                'Should be invalid offset value.',
            ],
            'integer lower bound valid' => [
                0,
                true,
                'Should be valid offset value.',
            ],
            'integer upper bound valid' => [
                1,
                true,
                'Should be valid offset value.',
            ],
            'string decimal above max invalid' => [
                '1.1',
                false,
                'Should be invalid offset value.',
            ],
            'string decimal within range' => [
                '0.5',
                true,
                'Should be valid offset value.',
            ],
            'string with percentage above max invalid' => [
                '101%',
                false,
                'Should be invalid offset value.',
            ],
            'string with percentage lower bound valid' => [
                '0%',
                true,
                'Should be valid offset value.',
            ],
            'string with percentage negative invalid' => [
                '-1%',
                false,
                'Should be invalid offset value.',
            ],
            'string with percentage plus sign valid' => [
                '+10%',
                true,
                'Should be valid offset value.',
            ],
            'string with percentage upper bound valid' => [
                '100%',
                true,
                'Should be valid offset value.',
            ],
            'string with percentage' => [
                '5%',
                true,
                'Should be valid offset value.',
            ],
        ];
    }

    /**
     * @phpstan-return array<string, array{string|UnitEnum, mixed, list<mixed>, bool, string}>
     */
    public static function oneOf(): array
    {
        return [
            'backed enum argument name' => [
                BackedString::VALUE,
                BackedString::VALUE,
                BackedString::cases(),
                false,
                '',
            ],
            'backed enum argument name not in list' => [
                BackedString::VALUE,
                'invalid_value',
                BackedString::cases(),
                true,
                Message::VALUE_NOT_IN_LIST->getMessage(
                    'invalid_value',
                    BackedString::VALUE->value,
                    implode('\', \'', Enum::normalizeArray(BackedString::cases())),
                ),
            ],
            'backed enum value in list' => [
                'attribute',
                BackedString::VALUE,
                BackedString::cases(),
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
                BackedString::VALUE,
                BackedInteger::cases(),
                true,
                Message::VALUE_NOT_IN_LIST->getMessage(
                    BackedString::VALUE->value,
                    'attribute',
                    implode('\', \'', Enum::normalizeArray(BackedInteger::cases())),
                ),
            ],
            'mixed enum types backed enum value found' => [
                'attribute',
                'value',
                [
                    BackedString::VALUE,
                    BackedInteger::VALUE,
                ],
                false,
                '',
            ],
            'mixed enum types enum instance found' => [
                'attribute',
                BackedString::VALUE,
                [
                    BackedString::VALUE,
                    BackedInteger::VALUE,
                ],
                false,
                '',
            ],
            'mixed enum types int value found' => [
                'attribute',
                1,
                [
                    BackedString::VALUE,
                    BackedInteger::VALUE,
                ],
                false,
                '',
            ],
            'mixed enum types string not found type strictness' => [
                'attribute',
                '1',
                [
                    BackedString::VALUE,
                    BackedInteger::VALUE,
                ],
                true,
                Message::VALUE_NOT_IN_LIST->getMessage(
                    '1',
                    'attribute',
                    implode('\', \'', Enum::normalizeArray([BackedString::VALUE, BackedInteger::VALUE])),
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
                'VALUE',
                [
                    BackedString::VALUE,
                ],
                true,
                Message::VALUE_NOT_IN_LIST->getMessage(
                    'VALUE',
                    'attribute',
                    implode('\', \'', Enum::normalizeArray([BackedString::VALUE])),
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
                Unit::value,
                Unit::cases(),
                false,
                '',
            ],
        ];
    }

    /**
     * @phpstan-return array<string, array{int|float|string|Stringable, float|null, float|null, bool, string}>
     */
    public static function positiveLike(): array
    {
        return [
            'float equal min' => [
                10.0,
                10.0,
                null,
                true,
                'Should be valid value.',
            ],
            'float equal max' => [
                10.0,
                null,
                10.0,
                true,
                'Should be valid value.',
            ],
            'float negative invalid' => [
                -1.5,
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'float below min' => [
                15.5,
                16.0,
                null,
                false,
                'Should be invalid value.',
            ],
            'float above max' => [
                15.5,
                null,
                10.0,
                false,
                'Should be invalid value.',
            ],
            'float positive valid' => [
                1.5,
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'float above min' => [
                5.5,
                5.0,
                null,
                true,
                'Should be valid value.',
            ],
            'float below max' => [
                2.5,
                null,
                10.0,
                true,
                'Should be valid value.',
            ],
            'float zero valid default min' => [
                0.0,
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'float in range 0.0 to 1.0 lower bound' => [
                0.0,
                0.0,
                1.0,
                true,
                'Should be valid value (stroke-miterlimit).',
            ],
            'float in range 0.0 to 1.0 upper bound' => [
                1.0,
                0.0,
                1.0,
                true,
                'Should be valid value (stroke-miterlimit).',
            ],
            'float in range 0.0 to 1.0 middle' => [
                0.5,
                0.0,
                1.0,
                true,
                'Should be valid value (stroke-miterlimit).',
            ],
            'float below range 0.0 to 1.0' => [
                -0.1,
                0.0,
                1.0,
                false,
                'Should be invalid value.',
            ],
            'float above range 0.0 to 1.0' => [
                1.1,
                0.0,
                1.0,
                false,
                'Should be invalid value.',
            ],
            'integer equal min' => [
                11,
                11.0,
                null,
                true,
                'Should be valid value.',
            ],
            'integer equal max' => [
                10,
                null,
                10.0,
                true,
                'Should be valid value.',
            ],
            'integer negative invalid' => [
                -1,
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'integer below min' => [
                15,
                16.0,
                null,
                false,
                'Should be invalid value.',
            ],
            'integer above max' => [
                15,
                null,
                10.0,
                false,
                'Should be invalid value.',
            ],
            'integer positive valid' => [
                1,
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'integer above min' => [
                6,
                5.0,
                null,
                true,
                'Should be valid value.',
            ],
            'integer below max' => [
                5,
                null,
                10.0,
                true,
                'Should be valid value.',
            ],
            'integer zero valid default min' => [
                0,
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'string decimal equal min' => [
                '11.0',
                11.0,
                null,
                true,
                'Should be valid value.',
            ],
            'string decimal equal max' => [
                '10.0',
                null,
                10.0,
                true,
                'Should be valid value.',
            ],
            'string decimal below min' => [
                '15.5',
                16.0,
                null,
                false,
                'Should be invalid value.',
            ],
            'string decimal above max' => [
                '15.5',
                null,
                10.0,
                false,
                'Should be invalid value.',
            ],
            'string decimal positive valid' => [
                '1.5',
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'string decimal above min' => [
                '5.5',
                5.0,
                null,
                true,
                'Should be valid value.',
            ],
            'string decimal below max' => [
                '2.5',
                null,
                10.0,
                true,
                'Should be valid value.',
            ],
            'string decimal in range 0.0 to 1.0' => [
                '0.75',
                0.0,
                1.0,
                true,
                'Should be valid value (stroke-miterlimit).',
            ],
            'string equal min' => [
                '10',
                10.0,
                null,
                true,
                'Should be valid value.',
            ],
            'string equal max' => [
                '10',
                null,
                10.0,
                true,
                'Should be valid value.',
            ],
            'string below min' => [
                '15',
                16.0,
                null,
                false,
                'Should be invalid value.',
            ],
            'string integer above max' => [
                '15',
                null,
                10.0,
                false,
                'Should be invalid value.',
            ],
            'string integer positive valid' => [
                '1',
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'string integer above min' => [
                '6',
                5.0,
                null,
                true,
                'Should be valid value.',
            ],
            'string integer below max' => [
                '5',
                null,
                10.0,
                true,
                'Should be valid value.',
            ],
            'string negative float invalid' => [
                '-1.5',
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'string negative invalid' => [
                '-1',
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'string non numeric invalid' => [
                'abc',
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'string scientific notation invalid' => [
                '1e3',
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'string with leading space invalid' => [
                ' 1.5',
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'string with plus sign float invalid' => [
                '+1.5',
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'string with plus sign invalid' => [
                '+1',
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'string with trailing space invalid' => [
                '1.5 ',
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'string zero valid default min' => [
                '0.0',
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'string zero integer valid default min' => [
                '0',
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'string zero negative float invalid' => [
                '-0.0',
                null,
                null,
                false,
                'Should be invalid value.',
            ],
            'string zero negative invalid' => [
                '-0',
                null,
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
                null,
                true,
                'Should be valid value.',
            ],
            'stringable above min' => [
                new class {
                    public function __toString(): string
                    {
                        return '5.5';
                    }
                },
                5.0,
                null,
                true,
                'Should be valid value.',
            ],
            'stringable below max' => [
                new class {
                    public function __toString(): string
                    {
                        return '5.5';
                    }
                },
                null,
                10.0,
                true,
                'Should be valid value.',
            ],
            'stringable zero valid default min' => [
                new class {
                    public function __toString(): string
                    {
                        return '0';
                    }
                },
                null,
                null,
                true,
                'Should be valid value.',
            ],
            'stringable in range 0.0 to 1.0' => [
                new class {
                    public function __toString(): string
                    {
                        return '0.75';
                    }
                },
                0.0,
                1.0,
                true,
                'Should be valid value (stroke-miterlimit).',
            ],
        ];
    }
}
