<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\CSSClass;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Providers\CSSClassProvider;
use UIAwesome\Html\Helper\Tests\Support\Stub\Enum\AlertType;
use UnitEnum;

/**
 * Unit tests for {@see CSSClass} helper functionality and behavior.
 *
 * Validates merging, rendering, and validation of CSS class values to ensure deterministic class attribute generation for
 * HTML fragments and lightweight components.
 *
 * Ensures correct handling of multiple class input formats, override semantics, enum-backed values, and
 * validation/error reporting for disallowed values.
 *
 * Test coverage.
 * - Exception handling for invalid scalar and enum values.
 * - Merging and add semantics for class attribute values, including override behavior.
 * - Rendering of class values with allowed lists and base-class formatting.
 *
 * {@see CSSClassProvider} for data-driven test cases and edge conditions.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helper')]
final class CSSClassTest extends TestCase
{
    /**
     * @throws InvalidArgumentException for invalid value errors.
     *
     * @phpstan-param mixed[] $attributes
     * @phpstan-param list<array{classes: mixed[]|string|UnitEnum|null, override?: bool}> $operations
     * @phpstan-param mixed[] $expected
     */
    #[DataProviderExternal(CSSClassProvider::class, 'values')]
    public function testAddClassAttributeValue(
        array $attributes,
        array $operations,
        array $expected,
        string $message,
    ): void {
        foreach ($operations as $operation) {
            $override = $operation['override'] ?? null;

            match ($override) {
                true => CSSClass::add($attributes, $operation['classes'], true),
                default => CSSClass::add($attributes, $operation['classes']),
            };
        }

        self::assertSame(
            $expected,
            $attributes,
            $message,
        );
    }

    /**
     * @phpstan-param list<string|UnitEnum> $allowed
     */
    #[DataProviderExternal(CSSClassProvider::class, 'renderValues')]
    public function testRenderClassValue(
        string|UnitEnum $class,
        string $baseClass,
        array $allowed,
        string $expected,
        string $message,
    ): void {
        self::assertSame(
            $expected,
            CSSClass::render($class, $baseClass, $allowed),
            $message,
        );
    }

    public function testThrowInvalidArgumentExceptionForInvalidClassValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::VALUE_NOT_IN_LIST->getMessage(
                'indigo',
                'class',
                implode('\', \'', ['blue', 'gray', 'green', 'red', 'yellow']),
            ),
        );

        CSSClass::render(
            'indigo',
            'p-4 mb-4 text-sm text-%1$s-800 rounded-lg bg-%1$s-50 dark:bg-gray-800 dark:text-%1$s-400',
            ['blue', 'gray', 'green', 'red', 'yellow'],
        );
    }

    public function testThrowInvalidArgumentExceptionForInvalidEnum(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::VALUE_NOT_IN_LIST->getMessage(
                'info',
                'class',
                implode('\', \'', ['success', 'warning', 'error']),
            ),
        );

        CSSClass::render(AlertType::INFO, 'alert alert-%s', ['success', 'warning', 'error']);
    }
}
