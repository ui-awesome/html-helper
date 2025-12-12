<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Naming;
use UIAwesome\Html\Helper\Tests\Providers\NamingProvider;

/**
 * Test suite for {@see Naming} helper functionality and behavior.
 *
 * Validates generation and normalization of form input names, element IDs, and pattern conversions used for HTML
 * attribute and form handling according to common web standards and framework conventions.
 *
 * Ensures correct handling of arrayable names, tabular input patterns, ID generation with prefixes, and strict
 * validation of property and form names to prevent malformed attributes in rendered markup.
 *
 * Test coverage.
 * - Conversion of regular expressions into usable patterns with optional delimiters.
 * - Deterministic ID generation and prefix handling.
 * - Generation of arrayable input names and tabular input validation.
 * - Validation and exception handling for invalid form/model and property names.
 *
 * {@see NamingProvider} for data-driven test cases and edge conditions.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helpers')]
final class NamingTest extends TestCase
{
    #[DataProviderExternal(NamingProvider::class, 'regularExpressionPattern')]
    public function testConvertToPattern(string $expected, string $regexp, string|null $delimiter = null): void
    {
        self::assertSame(
            $expected,
            Naming::convertToPattern($regexp, $delimiter),
            'Should convert to pattern as expected for each data set.',
        );
    }

    #[DataProviderExternal(NamingProvider::class, 'regularExpressionPatternInvalid')]
    public function testConvertToPatternWithValueInvalid(
        string $regexp,
        string|null $delimiter,
        string $message,
    ): void {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        Naming::convertToPattern($regexp, $delimiter);
    }

    public function testGenerateArrayableNameProducesArrayableName(): void
    {
        self::assertSame(
            'test.name[]',
            Naming::generateArrayableName('test.name'),
            'Should generate arrayable name as expected.',
        );
    }

    #[DataProviderExternal(NamingProvider::class, 'arrayableName')]
    public function testGenerateArrayableNameProducesExpectedOutput(string $attribute, string $expected): void
    {
        self::assertSame(
            $expected,
            Naming::generateArrayableName($attribute),
            'Should generate arrayable name for the provided attribute as expected.',
        );
    }

    public function testGenerateIdMatchesHexPattern(): void
    {
        self::assertMatchesRegularExpression(
            '/^id-[0-9a-f]{13}$/',
            Naming::generateId(),
            'Should generate an ID matching the expected hex pattern.',
        );
    }

    public function testGenerateIdWithPrefixMatchesPrefixedHexPattern(): void
    {
        self::assertMatchesRegularExpression(
            '/^prefix-[0-9a-f]{13}$/',
            Naming::generateId('prefix-'),
            'Should generate a prefixed ID matching the expected hex pattern.',
        );
    }

    #[DataProviderExternal(NamingProvider::class, 'inputName')]
    public function testGenerateInputNameProducesExpectedName(string $formName, string $attribute, bool $arrayable, string $expected): void
    {
        $name = match ($arrayable) {
            true => Naming::generateInputName($formName, $attribute, true),
            default => Naming::generateInputName($formName, $attribute),
        };

        self::assertSame(
            $expected,
            $name,
            'Should generate input name for given form and attribute according to arrayable flag.',
        );
    }

    public function testThrowExceptionForEmptyFormModelWithTabularInput(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::FORM_MODEL_NAME_CANNOT_BE_EMPTY->getMessage(),
        );

        Naming::generateInputName('', '[0]dates[0]');
    }

    public function testThrowExceptionForInvalidPropertyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Property name must contain word characters only.');

        Naming::generateInputName('TestForm', 'content body');
    }
}
