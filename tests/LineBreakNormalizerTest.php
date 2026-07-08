<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\LineBreakNormalizer;
use UIAwesome\Html\Helper\Tests\Provider\LineBreakNormalizerProvider;

/**
 * Unit tests for the {@see LineBreakNormalizer} helper.
 *
 * {@see LineBreakNormalizerProvider} for test case data providers.
 */
#[Group('helper')]
final class LineBreakNormalizerTest extends TestCase
{
    #[DataProviderExternal(LineBreakNormalizerProvider::class, 'normalize')]
    public function testNormalizeIsIdempotentAfterNormalization(string|null $content, string $expected): void
    {
        self::assertSame(
            $expected,
            LineBreakNormalizer::normalize(LineBreakNormalizer::normalize($content)),
            'Should produce stable output when normalized more than once.',
        );
    }

    #[DataProviderExternal(LineBreakNormalizerProvider::class, 'normalize')]
    public function testNormalizeReturnsExpectedOutput(string|null $content, string $expected): void
    {
        self::assertSame(
            $expected,
            LineBreakNormalizer::normalize($content),
            'Should normalize line breaks according to the provided data set.',
        );
    }
}
