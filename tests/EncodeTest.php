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
 * Validates encoding routines for content and value contexts to ensure deterministic and safe HTML escaping suitable
 * for fragments and lightweight components.
 *
 * Ensures correct handling of entity encoding, prevention of double-encoding when requested, and support for mixed
 * scalar types including `null`, int, float, and string.
 *
 * Test coverage.
 * - Deterministic output for edge cases supplied by the data provider.
 * - Encoding of mixed typed values and `null` handling.
 * - Encoding of plain text content with and without double-encoding.
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
        $encodeContent = match ($doubleEncode) {
            true => Encode::content($value),
            false => Encode::content($value, $doubleEncode),
        };

        $doubleEncodeValue = $doubleEncode ? 'true' : 'false';

        self::assertSame(
            $expected,
            $encodeContent,
            "Should encode content ({$value}) as ({$expected}) with doubleEncode='{$doubleEncodeValue}'.",
        );
    }

    #[DataProviderExternal(EncodeProvider::class, 'value')]
    public function testEncodeValueHandlesMixedTypesAndDoubleEncoding(
        float|int|string|null $value,
        string $expected,
        bool $doubleEncode,
    ): void {
        $encodeValue = match ($doubleEncode) {
            true => Encode::value($value),
            false => Encode::value($value, $doubleEncode),
        };

        $doubleEncodeValue = $doubleEncode ? 'true' : 'false';

        self::assertSame(
            $expected,
            $encodeValue,
            "Should encode value ({$value}) as ({$expected}) with doubleEncode='{$doubleEncodeValue}'.",
        );
    }
}
