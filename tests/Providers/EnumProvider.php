<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

use UIAwesome\Html\Helper\Tests\Support\Stub\Enum\{Status, Theme};

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\EnumTest} class.
 *
 * Supplies comprehensive test data for validating enum normalization in tag rendering, ensuring standards-compliant
 * conversion, type safety, and value propagation according to the PHP specification.
 *
 * The test data covers real-world scenarios for normalizing `BackedEnum` instances and `UnitEnum` instances, supporting
 * both single enum values and arrays containing mixed types, to maintain consistent scalar representation across
 * different rendering configurations.
 *
 * The provider organizes test cases with descriptive names for clear identification of failure cases during test
 * execution and debugging sessions.
 *
 * Key features.
 * - Ensures correct normalization of `BackedEnum` instances and `UnitEnum` instances to their scalar representation.
 * - Named test data sets for precise failure identification.
 * - Validation of mixed arrays containing enums and scalar values.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class EnumProvider
{
    /**
     * Provides test cases for array normalization scenarios.
     *
     * Supplies test data for validating the normalization of arrays containing `BackedEnum` instances, `UnitEnum`
     * instances, `null` values, and mixed scalar values, ensuring correct conversion to scalar representation for enums
     * while preserving non-enum values.
     *
     * Each test case includes the input array, expected normalized output, and an assertion message for clear failure
     * identification.
     *
     * @return array Test data for array normalization scenarios.
     *
     * @phpstan-return array<string, array{mixed[], mixed[], string}>
     */
    public static function normalizeArray(): array
    {
        return [
            'array of backed enums' => [
                [Status::ACTIVE, Status::INACTIVE],
                ['active', 'inactive'],
                'Should return an array of name values for backed enums.',
            ],
            'array of unit enums' => [
                [Theme::DARK, Theme::LIGHT],
                ['DARK', 'LIGHT'],
                'Should return an array of name values for unit enums.',
            ],
            'array with null values' => [
                [null, Status::ACTIVE, null],
                [null, 'active', null],
                'Should pass through null values unchanged.',
            ],
            'empty array' => [
                [],
                [],
                'Should return an empty array for empty input.',
            ],
            'mixed array with enums and scalars' => [
                ['foo', Status::ACTIVE, 42],
                ['foo', 'active', 42],
                'Should normalize enums and pass through scalars.',
            ],
        ];
    }

    /**
     * Provides test cases for single value normalization scenarios.
     *
     * Supplies test data for validating the normalization of `BackedEnum` instances, `UnitEnum` instances, and scalar
     * values, ensuring correct scalar conversion for enums and pass-through for non-enum values.
     *
     * Each test case includes the input value, expected normalized output, and an assertion message for clear failure
     * identification.
     *
     * @return array Test data for value normalization scenarios.
     *
     * @phpstan-return array<string, array{mixed, mixed, string}>
     */
    public static function normalizeValue(): array
    {
        return [
            'backed enum active' => [
                Status::ACTIVE,
                'active',
                'Should return the name value for a backed enum.',
            ],
            'backed enum inactive' => [
                Status::INACTIVE,
                'inactive',
                'Should return the name value for a backed enum.',
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
