<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\AttributeBag;
use UIAwesome\Html\Helper\Attributes;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Provider\AttributeBagProvider;
use UnitEnum;

/**
 * Unit tests for the {@see AttributeBag} helper.
 *
 * Test coverage.
 * - Replaces attribute arrays with normalized values.
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
     * @param mixed[] $attributes
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
     * @param mixed[] $attributes
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'getWithPrefix')]
    public function testGetWithPrefix(
        array $attributes,
        string|UnitEnum $key,
        string $prefix,
        mixed $default,
        mixed $expected,
    ): void {
        self::assertSame(
            $expected,
            AttributeBag::get($attributes, $key, $default, $prefix),
            'Should return existing prefixed value or fallback default.',
        );
    }

    #[DataProviderExternal(AttributeBagProvider::class, 'key')]
    public function testNormalizeKeyAttribute(mixed $key, string $prefix, string $expected): void
    {
        self::assertSame(
            $expected,
            AttributeBag::normalizeKey($key, $prefix),
            'Should normalize key attribute correctly.',
        );
    }

    /**
     * @param mixed[] $attributes
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'remove')]
    public function testRemove(array $attributes, string|UnitEnum $key, string $expected): void
    {
        AttributeBag::remove($attributes, $key);

        self::assertSame(
            $expected,
            Attributes::render($attributes),
            'Should remove the specified key in rendered output.',
        );
    }

    /**
     * @param mixed[] $attributes
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'removeWithPrefix')]
    public function testRemoveWithPrefix(array $attributes, string|UnitEnum $key, string $prefix, string $expected): void
    {
        AttributeBag::remove($attributes, $key, $prefix);

        self::assertSame(
            $expected,
            Attributes::render($attributes),
            'Should remove the specified prefixed key in rendered output.',
        );
    }

    /**
     * @param mixed[] $attributes
     * @param mixed[] $values
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'replace')]
    public function testReplace(array $attributes, array $values, string $expected, string|null $prefix = null): void
    {
        if ($prefix === null) {
            AttributeBag::replace($attributes, $values);
        } else {
            AttributeBag::replace($attributes, $values, $prefix);
        }

        self::assertSame(
            $expected,
            Attributes::render($attributes),
            'Should replace values and render the normalized output.',
        );
    }

    public function testReplacePreservesOriginalAttributesWhenReplacementFails(): void
    {
        $attributes = ['id' => 'submit'];

        try {
            AttributeBag::replace($attributes, ['title' => 'Submit', '' => 'invalid']);
            self::fail('Should throw an exception for an invalid replacement key.');
        } catch (InvalidArgumentException $exception) {
            self::assertSame(
                Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage(),
                $exception->getMessage(),
                'Should throw the expected invalid key message.',
            );
        }

        self::assertSame(
            ' id="submit"',
            Attributes::render($attributes),
            'Should keep the original attributes when replacement validation fails.',
        );
    }

    /**
     * @param mixed[] $attributes
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'set')]
    public function testSet(
        array $attributes,
        string|UnitEnum $key,
        mixed $value,
        string $expected,
    ): void {
        AttributeBag::set($attributes, $key, $value);

        self::assertSame(
            $expected,
            Attributes::render($attributes),
            'Should set plain raw values with key normalization.',
        );
    }

    /**
     * @param mixed[] $attributes
     * @param mixed[] $values
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'setMany')]
    public function testSetMany(array $attributes, array $values, string $expected): void
    {
        AttributeBag::setMany($attributes, $values);

        self::assertSame(
            $expected,
            Attributes::render($attributes),
            "Should set many plain attributes and remove keys with 'null' values in rendered output.",
        );
    }

    /**
     * @param mixed[] $attributes
     * @param mixed[] $values
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'setManyWithPrefix')]
    public function testSetManyWithPrefix(array $attributes, array $values, string $prefix, string $expected): void
    {
        AttributeBag::setMany($attributes, $values, $prefix);

        self::assertSame(
            $expected,
            Attributes::render($attributes),
            "Should set many prefixed attributes and remove normalized keys with 'null' values.",
        );
    }

    /**
     * @param mixed $value Attribute value that resolves to `null`.
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'nullValue')]
    public function testSetWithNullValueRemovesAttributeFromBag(mixed $value): void
    {
        $attributes = ['id' => 'submit'];

        AttributeBag::set($attributes, 'id', $value);

        self::assertSame(
            [],
            $attributes,
            'Should remove the attribute from the raw bag when the value resolves to `null`.',
        );
    }

    /**
     * @param mixed[] $attributes
     */
    #[DataProviderExternal(AttributeBagProvider::class, 'setWithPrefix')]
    public function testSetWithPrefix(
        array $attributes,
        string|UnitEnum $key,
        mixed $value,
        string $prefix,
        string $expected,
    ): void {
        AttributeBag::set($attributes, $key, $value, $prefix);

        self::assertSame(
            $expected,
            Attributes::render($attributes),
            'Should set prefixed attributes with normalized keys and serialized booleans.',
        );
    }

    #[DataProviderExternal(AttributeBagProvider::class, 'normalizeInvalidKey')]
    public function testThrowInvalidArgumentExceptionForAttributeKeyIsInvalid(mixed $key, string $prefix): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage(),
        );

        AttributeBag::normalizeKey($key, $prefix);
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
     * @param mixed[] $values
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
