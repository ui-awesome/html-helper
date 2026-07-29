<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Provider\MessageProvider;

/**
 * Unit tests for the {@see Message} enum public surface.
 *
 * {@see MessageProvider} for test case data providers.
 */
#[Group('helper')]
final class MessageTest extends TestCase
{
    /**
     * @param array<string, string> $expected
     */
    #[DataProviderExternal(MessageProvider::class, 'contract')]
    public function testCasesExposeTheExpectedNamesAndBackedValues(array $expected): void
    {
        $actual = [];

        foreach (Message::cases() as $case) {
            $actual[$case->name] = $case->value;
        }

        self::assertSame(
            $expected,
            $actual,
            'Should preserve every case name, backed value, and declaration order.',
        );
    }

    /**
     * @param list<int|string> $arguments
     */
    #[DataProviderExternal(MessageProvider::class, 'getMessage')]
    public function testGetMessageInterpolatesTheProvidedArguments(
        Message $case,
        array $arguments,
        string $expected,
    ): void {
        self::assertSame(
            $expected,
            $case->getMessage(...$arguments),
            'Should interpolate the arguments into the message template.',
        );
    }
}
