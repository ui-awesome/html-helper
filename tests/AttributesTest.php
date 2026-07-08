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
 * {@see AttributesProvider} for test case data providers.
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
