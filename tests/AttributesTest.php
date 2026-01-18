<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Attributes;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Providers\AttributesProvider;

/**
 * Unit tests for {@see Attributes} attribute rendering behavior.
 *
 * Verifies observable behavior for {@see Attributes} based on this test file only (test methods, providers, and
 * assertions). Statements must be grounded in datasets, assertions, and explicit exception expectations present here.
 *
 * Test coverage.
 * - Attribute key normalization and validation.
 * - Attribute rendering order and deterministic output.
 * - Attribute rendering with enums and `null`.
 * - Sanitization of malicious attribute values.
 *
 * {@see Attributes} for implementation details.
 * {@see AttributesProvider} for test case data providers.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helper')]
final class AttributesTest extends TestCase
{
    /**
     * @phpstan-param mixed[] $attributes
     * @phpstan-param mixed[] $expected
     */
    #[DataProviderExternal(AttributesProvider::class, 'normalizeAttributes')]
    public function testNormalizeAttributes(array $attributes, array $expected, bool $encode = true): void
    {
        $normalizeAttribute = match ($encode) {
            true => Attributes::normalizeAttributes($attributes),
            false => Attributes::normalizeAttributes($attributes, false),
        };

        self::assertSame(
            $expected,
            $normalizeAttribute,
            'Should normalize attributes returning the expected array structure.',
        );
    }

    #[DataProviderExternal(AttributesProvider::class, 'key')]
    public function testNormalizeKeyAttribute(mixed $key, string $prefix, string $expected): void
    {
        self::assertSame(
            $expected,
            Attributes::normalizeKey($key, $prefix),
            'Should normalize key attribute correctly.',
        );
    }

    /**
     * @phpstan-param mixed[] $attributes
     */
    #[DataProviderExternal(AttributesProvider::class, 'attributeOrdering')]
    public function testRenderAttributeOrdering(string $expected, array $attributes): void
    {
        self::assertSame(
            $expected,
            Attributes::render($attributes),
            'Should render attributes in the expected order.',
        );
    }

    /**
     * @phpstan-param mixed[] $attributes
     */
    #[DataProviderExternal(AttributesProvider::class, 'emptyAndNullValues')]
    public function testRenderEmptyAndNullValues(string $expected, array $attributes): void
    {
        self::assertSame(
            $expected,
            Attributes::render($attributes),
            "Should handle empty and 'null' values as expected for each data set.",
        );
    }

    /**
     * @phpstan-param mixed[] $attributes
     */
    #[DataProviderExternal(AttributesProvider::class, 'enumAttribute')]
    public function testRenderEnumAttributes(string $expected, array $attributes): void
    {
        self::assertSame(
            $expected,
            Attributes::render($attributes),
            'Should render enum attributes as expected for each data set.',
        );
    }

    /**
     * @phpstan-param mixed[] $attributes
     */
    #[DataProviderExternal(AttributesProvider::class, 'maliciousValues')]
    public function testRenderMaliciousValues(string $expected, array $attributes): void
    {
        self::assertSame(
            $expected,
            Attributes::render($attributes),
            'Should sanitize malicious values to prevent XSS and security vulnerabilities.',
        );
    }

    /**
     * @phpstan-param mixed[] $attributes
     */
    #[DataProviderExternal(AttributesProvider::class, 'styleAttributes')]
    public function testRenderStyleAttributes(string $expected, array $attributes): void
    {
        self::assertSame(
            $expected,
            Attributes::render($attributes),
            'Should render style attributes as expected for each data set.',
        );
    }

    /**
     * @phpstan-param mixed[] $attributes
     */
    #[DataProviderExternal(AttributesProvider::class, 'renderTagAttributes')]
    public function testRenderTagAttributes(string $expected, array $attributes): void
    {
        self::assertSame(
            $expected,
            Attributes::render($attributes),
            'Should render attributes as expected for each data set.',
        );
    }

    #[DataProviderExternal(AttributesProvider::class, 'invalidKey')]
    public function testThrowInvalidArgumentExceptionForAttributeKeyIsInvalid(mixed $key, string $prefix): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::KEY_MUST_BE_NON_EMPTY_STRING->getMessage(),
        );

        Attributes::normalizeKey($key, $prefix);
    }
}
