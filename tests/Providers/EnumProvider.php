<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

use Stringable;
use UIAwesome\Html\Helper\Tests\Support\Stub\Enum\{Status, Theme};

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\EnumTest} class.
 *
 * Supplies focused datasets used by enum normalization utilities.
 *
 * The cases verify conversion of backed enums to their scalar, conversion of unit enums to their name strings,
 * preservation of `null`, and passthrough behavior for non-enum scalars.
 *
 * Key features.
 * - Normalize backed enums to their scalar-backed.
 * - Preserve `null` and non-enum scalar unchanged.
 * - Return unit enum names for UnitEnum implementations.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class EnumProvider
{
    /**
     * Provides datasets used to assert normalization of arrays containing enums and scalars.
     *
     * Each dataset returns the input array, the expected normalized array and a short description of the
     * expectation. These cases cover backed enums, unit enums, `null` passthrough, mixed arrays of enums, scalar and
     * stringable objects.
     *
     * @return array Test data for array normalization.
     *
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
     * Provides datasets for normalizing individual values.
     *
     * Each dataset returns the original value, the expected normalized value, and a description. Tests ensure backed
     * enums yield their scalar value, unit enums yield their name, non-enum scalars pass through unchanged and
     * stringable objects return their string representation.
     *
     * @return array Test data for value normalization.
     *
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
