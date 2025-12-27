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
 * Validates normalization routines for enum-backed values and arrays to ensure deterministic and framework-friendly
 * values suitable for attribute and class rendering and other helper operations.
 *
 * Ensures correct handling of `UnitEnum` instances, scalar values, `null`, and nested arrays, and verifies that
 * unsupported types raise appropriate exceptions with clear messages.
 *
 * Test coverage.
 * - Exception behavior for invalid value types.
 * - Normalization of arrays containing enums and mixed types.
 * - Normalization of single values including enum, scalar, and `null`.
 *
 * {@see EnumProvider} for data-driven test cases and edge conditions.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helper')]
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

    public function testThrowInvalidArgumentExceptionForInvalidValueType(): void
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
