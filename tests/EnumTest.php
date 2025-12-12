<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use stdClass;
use UIAwesome\Html\Helper\Enum;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Providers\EnumProvider;

/**
 * Test suite for {@see Enum} helper functionality and behavior.
 *
 * Validates the normalization and validation of enum values and arrays according to the PHP language specification.
 *
 * Ensures correct handling, immutability, and validation of enum operations, supporting both scalar and array types,
 * as well as exception handling for invalid value types.
 *
 * Test coverage.
 * - Accurate normalization of arrays containing enums and scalars.
 * - Compatibility with PHP enums and scalar types.
 * - Exception handling for invalid value types.
 * - Validation of values within a predefined list.
 *
 * {@see EnumProvider} for data-driven test cases and edge conditions.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helpers')]
final class EnumTest extends TestCase
{
    /**
     * @phpstan-param mixed[] $input
     * @phpstan-param mixed[] $expected
     */
    #[DataProviderExternal(EnumProvider::class, 'normalizeArray')]
    public function testNormalizeArrayWithEnums(array $input, array $expected, string $message): void
    {
        self::assertSame(
            $expected,
            Enum::normalizeArray($input),
            $message,
        );
    }

    #[DataProviderExternal(EnumProvider::class, 'normalizeValue')]
    public function testNormalizeValueWithEnums(mixed $input, mixed $expected, string $message): void
    {
        self::assertSame(
            $expected,
            Enum::normalizeValue($input),
            $message,
        );
    }

    public function testThrowExceptionForInvalidValueType(): void
    {
        $value = new stdClass();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::VALUE_SHOULD_BE_ARRAY_SCALAR_NULL_ENUM->getMessage(
                gettype($value),
            ),
        );

        Enum::normalizeValue($value);
    }
}
