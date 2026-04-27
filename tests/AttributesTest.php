<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Attributes;
use UIAwesome\Html\Helper\Tests\Provider\AttributesProvider;

/**
 * Unit tests for the {@see Attributes} helper.
 *
 * Test coverage.
 * - Normalizes attribute arrays with configurable encoding behavior.
 * - Normalizes attribute keys with prefix handling.
 * - Renders attributes in deterministic order.
 * - Renders attributes with enum and `null` values.
 * - Renders malicious inputs with safe output encoding.
 * - Renders style attributes from scalar, `array`, and stringable values.
 * - Renders tag attributes across `bool`, closure, and nested structures.
 * - Throws exceptions for invalid attribute keys.
 *
 * {@see AttributesProvider} for test case data providers.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helper')]
final class AttributesTest extends TestCase
{
    /**
     * @param mixed[] $attributes
     * @param mixed[] $expected
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

    /**
     * @param mixed[] $attributes
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
     * @param mixed[] $attributes
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
     * @param mixed[] $attributes
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
     * @param mixed[] $attributes
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
     * @param mixed[] $attributes
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
     * @param mixed[] $attributes
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
}
