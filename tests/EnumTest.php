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
 * Unit tests for {@see Enum} enum normalization behavior.
 *
 * Verifies observable behavior for {@see Enum} based on this test file only (test methods, providers, and
 * assertions). Statements must be grounded in datasets, assertions, and explicit exception expectations present here.
 *
 * Test coverage.
 * - Exception behavior for unsupported value types.
 * - Normalization of arrays containing enums and mixed values.
 * - Normalization of single values, including enums and `null`.
 *
 * {@see Enum} for implementation details.
 * {@see EnumProvider} for test case data providers.
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
