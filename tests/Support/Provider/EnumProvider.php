<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Support\Provider;

use Stringable;
use UIAwesome\Html\Helper\Tests\Support\Stub\Enum\{Status, Theme};

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\EnumTest} test cases.
 *
 * Provides representative input/output pairs for enum normalization utility methods.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class EnumProvider
{
    /**
     * @phpstan-return array<string, array{mixed[], mixed[], string}>
     */
    public static function normalizeArray(): array
    {
        return [
            'array of backed enums' => [
                [
                    Status::ACTIVE,
                    Status::INACTIVE,
                ],
                [
                    'active',
                    'inactive',
                ],
                'Should return an array of backed enum values.',
            ],
            'array of unit enums' => [
                [
                    Theme::DARK,
                    Theme::LIGHT,
                ],
                [
                    'DARK',
                    'LIGHT',
                ],
                'Should return an array of name values for unit enums.',
            ],
            'array with null values' => [
                [
                    null,
                    Status::ACTIVE,
                    null,
                ],
                [
                    null,
                    'active',
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
                    Status::ACTIVE,
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
                    'active',
                    42,
                    'stringable',
                ],
                'Should normalize enums and pass through scalars.',
            ],
        ];
    }

    /**
     * @phpstan-return array<string, array{mixed, mixed, string}>
     */
    public static function normalizeValue(): array
    {
        return [
            'backed enum active' => [
                Status::ACTIVE,
                'active',
                'Should return the backed enum value.',
            ],
            'backed enum inactive' => [
                Status::INACTIVE,
                'inactive',
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
            'unit enum dark' => [
                Theme::DARK,
                'DARK',
                'Should return the name value for a unit enum.',
            ],
            'unit enum light' => [
                Theme::LIGHT,
                'LIGHT',
                'Should return the name value for a unit enum.',
            ],
        ];
    }
}
