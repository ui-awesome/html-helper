<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPForge\Support\Stub\BackedString;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\CSSClass;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Provider\CSSClassProvider;
use UnitEnum;

/**
 * Unit tests for the {@see CSSClass} helper.
 *
 * {@see CSSClassProvider} for test case data providers.
 */
#[Group('helper')]
final class CSSClassTest extends TestCase
{
    /**
     * @param mixed[] $attributes
     * @param list<array{classes: mixed[]|string|UnitEnum|null, override?: bool}> $operations
     * @param mixed[] $expected
     * @throws InvalidArgumentException for invalid value errors.
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
     * @param list<string|UnitEnum> $allowed
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
                'value',
                'class',
                implode('\', \'', ['success', 'warning', 'error']),
            ),
        );

        CSSClass::render(BackedString::VALUE, 'alert alert-%s', ['success', 'warning', 'error']);
    }
}
