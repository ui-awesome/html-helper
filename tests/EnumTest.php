<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use stdClass;
use UIAwesome\Html\Helper\Enum;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Provider\EnumProvider;

/**
 * Unit tests for the {@see Enum} helper.
 *
 * {@see EnumProvider} for test case data providers.
 */
#[Group('helper')]
final class EnumTest extends TestCase
{
    /**
     * @param mixed[] $input
     * @param mixed[] $expected
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

    /**
     * @param mixed[] $input
     * @param string[] $expected
     */
    #[DataProviderExternal(EnumProvider::class, 'normalizeStringArray')]
    public function testNormalizeStringArrayWithEnums(array $input, array $expected, string $message): void
    {
        self::assertSame(
            $expected,
            Enum::normalizeStringArray($input),
            $message,
        );
    }

    #[DataProviderExternal(EnumProvider::class, 'normalizeStringValue')]
    public function testNormalizeStringValueWithEnums(mixed $input, string $expected, string $message): void
    {
        self::assertSame(
            $expected,
            Enum::normalizeStringValue($input),
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

    public function testThrowInvalidArgumentExceptionForInvalidStringValueType(): void
    {
        $value = new stdClass();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::VALUE_SHOULD_BE_ARRAY_SCALAR_NULL_ENUM->getMessage(
                gettype($value),
            ),
        );

        Enum::normalizeStringValue($value);
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
