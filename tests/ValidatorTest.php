<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Tests\Providers\ValidatorProvider;
use UIAwesome\Html\Helper\Validator;
use UnitEnum;

/**
 * Test suite for {@see Validator} helper functionality and behavior.
 *
 * Validates the normalization and verification of values for HTML attribute rendering and tag manipulation according to
 * the HTML Living Standard specification.
 *
 * Ensures correct type checking, value validation, and exception handling for scalar, enum, and array types in
 * attribute operations, supporting robust and predictable output for HTML components.
 *
 * Test coverage.
 * - Accurate validation of integer-like values and range constraints.
 * - Data provider-driven validation for edge cases and expected behaviors.
 * - Exception handling for invalid or out-of-range values.
 * - Immutability of the helper's API when validating values.
 * - Verification of allowed values for attributes and enums.
 *
 * {@see ValidatorProvider} for data-driven test cases and edge conditions.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helpers')]
final class ValidatorTest extends TestCase
{
    #[DataProviderExternal(ValidatorProvider::class, 'intLike')]
    public function testIntegerLike(int|string $value, int|null $min, int|null $max, bool $expected, string $message): void
    {
        self::assertSame(
            $expected,
            Validator::intLike($value, $min, $max),
            $message,
        );
    }

    /**
     * @throws InvalidArgumentException if one or more arguments are invalid, of incorrect type or format.
     *
     * @phpstan-param list<UnitEnum|scalar|null> $allowed
     */
    #[DataProviderExternal(ValidatorProvider::class, 'oneOf')]
    public function testOneOfWithValidValues(
        string $attribute,
        UnitEnum|int|string $value,
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
}
