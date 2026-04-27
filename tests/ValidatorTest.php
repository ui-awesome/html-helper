<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use ReflectionMethod;
use Stringable;
use UIAwesome\Html\Helper\Base\BaseValidator;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Tests\Provider\ValidatorProvider;
use UIAwesome\Html\Helper\Validator;
use UnitEnum;

/**
 * Unit tests for the {@see Validator} helper.
 *
 * Test coverage.
 * - Validates `int`-like values with optional `min` and `max` bounds.
 * - Validates offset-like values for ratios and percentage strings.
 * - Validates one-of membership and throws exceptions for disallowed values when configured.
 * - Validates positive-like values with optional `min` and `max` bounds.
 *
 * {@see ValidatorProvider} for test case data providers.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
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

    public function testOneOfFormatsArrayAllowedValueForExceptionMessage(): void
    {
        $method = new ReflectionMethod(BaseValidator::class, 'normalizeAllowedValue');

        self::assertSame(
            'Array',
            $method->invoke(null, ['state' => 'active']),
            'Should format array allowed values for exception messages.',
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
}
