<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Provider;

use PHPForge\Support\Stub\{BackedString, Unit};
use Stringable;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\EnumTest} test cases.
 *
 * Provides representative input/output pairs for enum normalization.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class EnumProvider
{
    /**
     * @return array<string, array{mixed[], mixed[], string}>
     */
    public static function normalizeArray(): array
    {
        return [
            'array of backed enums' => [
                [
                    BackedString::VALUE,
                ],
                [
                    'value',
                ],
                'Should return an array of backed enum values.',
            ],
            'array of unit enums' => [
                [
                    Unit::value,
                ],
                [
                    'value',
                ],
                'Should return an array of name values for unit enums.',
            ],
            'array with null values' => [
                [
                    null,
                    BackedString::VALUE,
                    null,
                ],
                [
                    null,
                    'value',
                    null,
                ],
                "Should pass through 'null' values unchanged.",
            ],
            'empty array' => [
                [],
                [],
                'Should return an empty array for empty input.',
            ],
            'mixed array with enums, scalars and Stringable' => [
                [
                    'foo',
                    BackedString::VALUE,
                    42,
                    new class implements Stringable {
                        public function __toString(): string
                        {
                            return 'stringable';
                        }
                    },
                ],
                [
                    'foo',
                    'value',
                    42,
                    'stringable',
                ],
                'Should normalize enums and pass through scalars.',
            ],
        ];
    }

    /**
     * @return array<string, array{mixed[], string[], string}>
     */
    public static function normalizeStringArray(): array
    {
        return [
            'array of backed enums' => [
                [
                    BackedString::VALUE,
                ],
                [
                    'value',
                ],
                'Should return an array of backed enum values as strings.',
            ],
            'array of unit enums' => [
                [
                    Unit::value,
                ],
                [
                    'value',
                ],
                'Should return an array of name values for unit enums as strings.',
            ],
            'empty array' => [
                [],
                [],
                'Should return an empty array for empty input.',
            ],
            'mixed array with textual representations' => [
                [
                    null,
                    true,
                    false,
                    ['nested' => 'value'],
                    'foo',
                    42,
                    3.14,
                    BackedString::VALUE,
                    new class implements Stringable {
                        public function __toString(): string
                        {
                            return 'stringable';
                        }
                    },
                ],
                [
                    'null',
                    'true',
                    'false',
                    'Array',
                    'foo',
                    '42',
                    '3.14',
                    'value',
                    'stringable',
                ],
                'Should normalize all accepted values to deterministic strings.',
            ],
        ];
    }

    /**
     * @return array<string, array{mixed, mixed, string}>
     */
    public static function normalizeValue(): array
    {
        return [
            'backed enum' => [
                BackedString::VALUE,
                'value',
                'Should return the backed enum value.',
            ],
            'null' => [
                null,
                null,
                "Should return 'null' unchanged.",
            ],
            'scalar float' => [
                3.14,
                3.14,
                'Should return the original scalar value if not an enum.',
            ],
            'scalar integer' => [
                42,
                42,
                'Should return the original scalar value if not an enum.',
            ],
            'scalar string' => [
                'foo',
                'foo',
                'Should return the original scalar value if not an enum.',
            ],
            'stringable' => [
                new class implements Stringable {
                    public function __toString(): string
                    {
                        return 'stringable';
                    }
                },
                'stringable',
                'Should return the string representation for Stringable objects.',
            ],
            'unit enum' => [
                Unit::value,
                'value',
                'Should return the name value for a unit enum.',
            ],
        ];
    }

    /**
     * @return array<string, array{mixed, string, string}>
     */
    public static function normalizeStringValue(): array
    {
        return [
            'array' => [
                ['nested' => 'value'],
                'Array',
                'Should return a deterministic string representation for arrays.',
            ],
            'backed enum' => [
                BackedString::VALUE,
                'value',
                'Should return the backed enum value as a string.',
            ],
            'boolean false' => [
                false,
                'false',
                "Should return 'false' for false values.",
            ],
            'boolean true' => [
                true,
                'true',
                "Should return 'true' for true values.",
            ],
            'null' => [
                null,
                'null',
                "Should return 'null' as a string.",
            ],
            'scalar float' => [
                3.14,
                '3.14',
                'Should return the original scalar float as a string.',
            ],
            'scalar integer' => [
                42,
                '42',
                'Should return the original scalar integer as a string.',
            ],
            'scalar string' => [
                'foo',
                'foo',
                'Should return the original scalar string.',
            ],
            'stringable' => [
                new class implements Stringable {
                    public function __toString(): string
                    {
                        return 'stringable';
                    }
                },
                'stringable',
                'Should return the string representation for Stringable objects.',
            ],
            'unit enum' => [
                Unit::value,
                'value',
                'Should return the name value for a unit enum.',
            ],
        ];
    }
}
