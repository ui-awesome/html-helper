<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use Stringable;
use UIAwesome\Html\Helper\Encode;
use UIAwesome\Html\Helper\Tests\Support\Provider\EncodeProvider;

/**
 * Unit tests for {@see Encode} encoding behavior.
 *
 * Verifies observable behavior for {@see Encode} based on this test file only (test methods, providers, and
 * assertions). Statements must be grounded in datasets, assertions, and explicit exception expectations present here.
 *
 * Test coverage.
 * - Content encoding with and without double-encoding.
 * - Value encoding for mixed scalar types, including `null`.
 *
 * {@see Encode} for implementation details.
 * {@see EncodeProvider} for test case data providers.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helper')]
final class EncodeTest extends TestCase
{
    #[DataProviderExternal(EncodeProvider::class, 'content')]
    public function testEncodeContentHandlesEntitiesAndDoubleEncoding(
        string|Stringable $value,
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
        float|int|string|Stringable|null $value,
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
