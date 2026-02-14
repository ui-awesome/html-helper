<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\AttributeBag;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Provider\AttributeBagProvider;
use UnitEnum;

/**
 * Unit tests for the {@see AttributeBag} helper.
 *
 * Test coverage.
 * - Merges attribute arrays and overrides existing keys.
 * - Removes attributes for valid keys.
 * - Sets multiple attributes through dedicated `*Many()` APIs.
 * - Sets plain attributes with raw values.
 * - Throws exceptions for invalid keys in `get()`.
 * - Throws exceptions for invalid keys in `remove()`.
 * - Throws exceptions for invalid keys in `set()` and `*Many()` APIs.
 * - Verifies `get()` returns existing values or fallback defaults.
 *
 * {@see AttributeBagProvider} for test case data providers.
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helper')]
final class AttributeBagTest extends TestCase
{
    /**
     * @phpstan-param mixed[] $attributes
     * @phpstan-param string|UnitEnum $key
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'get')]
    public function testGet(array $attributes, string|UnitEnum $key, mixed $default, mixed $expected): void
    {
        self::assertSame(
            $expected,
            AttributeBag::get($attributes, $key, $default),
            'Should return existing value or fallback default.',
        );
    }

    /**
     * @phpstan-param mixed[] $attributes
     * @phpstan-param mixed[] $values
     * @phpstan-param mixed[] $expected
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'merge')]
    public function testMerge(array $attributes, array $values, array $expected): void
    {
        AttributeBag::merge($attributes, $values);

        self::assertSame(
            $expected,
            $attributes,
            'Should merge values and override duplicated keys.',
        );
    }

    /**
     * @phpstan-param mixed[] $attributes
     * @phpstan-param string|UnitEnum $key
     * @phpstan-param mixed[] $expected
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'remove')]
    public function testRemove(array $attributes, string|UnitEnum $key, array $expected): void
    {
        AttributeBag::remove($attributes, $key);

        self::assertSame(
            $expected,
            $attributes,
            'Should remove the specified key from attributes.',
        );
    }

    /**
     * @phpstan-param mixed[] $attributes
     * @phpstan-param string|UnitEnum $key
     * @phpstan-param mixed[] $expected
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'set')]
    public function testSet(
        array $attributes,
        string|UnitEnum $key,
        mixed $value,
        array $expected,
    ): void {
        AttributeBag::set($attributes, $key, $value);

        self::assertSame(
            $expected,
            $attributes,
            'Should set plain raw values with key normalization.',
        );
    }

    /**
     * @phpstan-param mixed[] $attributes
     * @phpstan-param mixed[] $values
     * @phpstan-param mixed[] $expected
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'setMany')]
    public function testSetMany(array $attributes, array $values, array $expected): void
    {
        AttributeBag::setMany($attributes, $values);

        self::assertSame(
            $expected,
            $attributes,
            "Should set many plain attributes and remove keys with 'null' values.",
        );
    }

    #[DataProviderExternal(AttributeBagProvider::class, 'invalidKey')]
    public function testThrowInvalidArgumentExceptionWhenGet(string|UnitEnum $key): void
    {
        $attributes = ['id' => 'submit'];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage());

        AttributeBag::get($attributes, $key);
    }

    #[DataProviderExternal(AttributeBagProvider::class, 'invalidKey')]
    public function testThrowInvalidArgumentExceptionWhenRemove(string|UnitEnum $key): void
    {
        $attributes = ['id' => 'submit'];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage());

        AttributeBag::remove($attributes, $key);
    }

    #[DataProviderExternal(AttributeBagProvider::class, 'invalidKey')]
    public function testThrowInvalidArgumentExceptionWhenSet(string|UnitEnum $key): void
    {
        $attributes = ['id' => 'submit'];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage());

        AttributeBag::set($attributes, $key, 'value');
    }

    /**
     * @phpstan-param mixed[] $values
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'invalidManyKey')]
    public function testThrowInvalidArgumentExceptionWhenSetMany(array $values, string $message): void
    {
        $attributes = ['id' => 'submit'];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        AttributeBag::setMany($attributes, $values);
    }
}
