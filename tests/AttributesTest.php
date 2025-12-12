<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Attributes;
use UIAwesome\Html\Helper\Tests\Providers\AttributesProvider;

/**
 * Test suite for {@see Attributes} helper functionality and behavior.
 *
 * Validates the rendering and manipulation of HTML attributes according to the HTML Living Standard specification.
 *
 * Ensures correct handling, ordering, encoding, and validation of HTML attributes in tag rendering, supporting array
 * values, boolean attributes, data/ARIA expansion, and XSS prevention for secure attribute output.
 *
 * Test coverage.
 * - Accurate rendering of HTML attributes with proper ordering.
 * - Correct handling of empty and null values.
 * - Data provider-driven validation for edge cases and expected behaviors.
 * - Enum attribute rendering and type conversion.
 * - Sanitization of malicious values to prevent XSS vulnerabilities.
 * - Style attribute formatting and rendering.
 *
 * {@see AttributesProvider} for data-driven test cases and edge conditions.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helpers')]
final class AttributesTest extends TestCase
{
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
}
