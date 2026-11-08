<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use ErrorException;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use Stringable;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Provider\ValidatorProvider;
use UIAwesome\Html\Helper\Validator;
use UnitEnum;

/**
 * Unit tests for the {@see Validator} helper.
 *
 * {@see ValidatorProvider} for test case data providers.
 */
#[Group('helper')]
final class ValidatorTest extends TestCase
{
    #[DataProviderExternal(ValidatorProvider::class, 'intLike')]
    public function testIntegerLike(
        int|string|Stringable $value,
        int|null $min,
        int|null $max,
        bool $expected,
        string $message,
    ): void {
        self::assertSame(
            $expected,
            Validator::intLike($value, $min, $max),
            $message,
        );
    }

    #[DataProviderExternal(ValidatorProvider::class, 'integerText')]
    public function testIntegerLikeWithStringable(string $value, int|null $min, int|null $max, bool $expected): void
    {
        set_error_handler(
            static function (int $severity, string $message): never {
                throw new ErrorException(
                    $message,
                    0,
                    $severity,
                );
            },
        );

        try {
            self::assertSame(
                $expected,
                Validator::intLike($value, $min, $max),
                'String validation must match the expected result.',
            );
            self::assertSame(
                $expected,
                Validator::intLike(self::stringable($value), $min, $max),
                'Stringable validation must match the equivalent string without warnings.',
            );
        } finally {
            restore_error_handler();
        }
    }

    #[DataProviderExternal(ValidatorProvider::class, 'offsetLike')]
    public function testOffsetLike(
        int|float|string|Stringable $value,
        bool $expected,
        string $message,
    ): void {
        self::assertSame(
            $expected,
            Validator::offsetLike($value),
            $message,
        );
    }

    #[DataProviderExternal(ValidatorProvider::class, 'offsetText')]
    public function testOffsetLikeWithStringable(string $value, bool $expected): void
    {
        self::assertSame(
            $expected,
            Validator::offsetLike($value),
            'String validation must match the expected result.',
        );
        self::assertSame(
            $expected,
            Validator::offsetLike(self::stringable($value)),
            'Stringable validation must match the equivalent string.',
        );
    }

    public function testOneOfFormatsNullAndBooleanAllowedValuesInExceptionMessage(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::VALUE_NOT_IN_LIST->getMessage('missing', 'attribute', "null', 'true', 'false"),
        );

        Validator::oneOf('missing', [null, true, false], 'attribute');
    }

    /**
     * @param list<scalar|UnitEnum|null> $allowed
     * @throws InvalidArgumentException if one or more arguments are invalid, of incorrect type or format.
     */
    #[DataProviderExternal(ValidatorProvider::class, 'oneOf')]
    public function testOneOfWithValidValues(
        string|UnitEnum $attribute,
        int|string|Stringable|UnitEnum|null $value,
        array $allowed,
        bool $exception,
        string $exceptionMessage,
    ): void {
        if ($exception) {
            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage($exceptionMessage);
        } else {
            $this->expectNotToPerformAssertions();
        }

        Validator::oneOf($value, $allowed, $attribute);
    }

    #[DataProviderExternal(ValidatorProvider::class, 'positiveLike')]
    public function testPositiveLike(
        int|float|string|Stringable $value,
        float|null $min,
        float|null $max,
        bool $expected,
        string $message,
    ): void {
        self::assertSame(
            $expected,
            Validator::positiveLike($value, $min, $max),
            $message,
        );
    }

    #[DataProviderExternal(ValidatorProvider::class, 'positiveText')]
    public function testPositiveLikeWithStringable(string $value, float|null $min, float|null $max, bool $expected): void
    {
        self::assertSame(
            $expected,
            Validator::positiveLike($value, $min, $max),
            'String validation must match the expected result.',
        );
        self::assertSame(
            $expected,
            Validator::positiveLike(self::stringable($value), $min, $max),
            'Stringable validation must match the equivalent string.',
        );
    }

    private static function stringable(string $value): Stringable
    {
        return new class ($value) implements Stringable {
            public function __construct(private readonly string $value) {}

            public function __toString(): string
            {
                return $this->value;
            }
        };
    }
}
