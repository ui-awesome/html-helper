<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Encode;
use UIAwesome\Html\Helper\Tests\Providers\EncodeProvider;

/**
 * Test suite for {@see Encode} helper functionality and behavior.
 *
 * Validates the encoding of content and values according to the HTML Living Standard specification.
 *
 * Ensures correct handling, immutability, and validation of encoding operations, supporting both scalar and mixed
 * types, as well as double encoding scenarios.
 *
 * Test coverage.
 * - Accurate encoding of content and values.
 * - Compatibility with scalar and mixed types.
 * - Data provider-driven validation for edge cases and expected behaviors.
 * - Proper handling of double encoding scenarios.
 *
 * {@see EncodeProvider} for data-driven test cases and edge conditions.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helpers')]
final class EncodeTest extends TestCase
{
    #[DataProviderExternal(EncodeProvider::class, 'content')]
    public function testEncodeContentHandlesEntitiesAndDoubleEncoding(
        string $value,
        string $expected,
        bool $doubleEncode,
    ): void {
        self::assertSame(
            $expected,
            Encode::content($value),
            "Should encode ({$value}) as ({$expected}) (doubleEncode: 'true').",
        );

        $doubleEncodeValue = $doubleEncode ? 'true' : 'false';

        self::assertSame(
            $expected,
            Encode::content($value, $doubleEncode),
            "Should encode ({$value}) as ({$expected}) with doubleEncode='{$doubleEncodeValue}'.",
        );
    }

    #[DataProviderExternal(EncodeProvider::class, 'value')]
    public function testEncodeValueHandlesMixedTypesAndDoubleEncoding(
        float|int|string|null $value,
        string $expected,
        bool $doubleEncode,
    ): void {
        self::assertSame(
            $expected,
            Encode::value($value),
            "Should encode value ({$value}) as ({$expected}) (doubleEncode: 'true').",
        );

        $doubleEncodeValue = $doubleEncode ? 'true' : 'false';

        self::assertSame(
            $expected,
            Encode::value($value, $doubleEncode),
            "Should encode value ({$value}) as ({$expected}) with doubleEncode='{$doubleEncodeValue}'.",
        );
    }
}
