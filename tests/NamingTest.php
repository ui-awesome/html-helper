<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Exception\Message;
use UIAwesome\Html\Helper\Naming;
use UIAwesome\Html\Helper\Tests\Providers\NamingProvider;

use function ini_get;
use function ini_set;
use function str_repeat;

/**
 * Unit tests for {@see Naming} helper functionality and behavior.
 *
 * Validates generation and normalization of form input names, element IDs, and pattern conversions used for HTML
 * attribute and form handling.
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
#[Group('helper')]
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

    #[DataProviderExternal(NamingProvider::class, 'arrayableName')]
    public function testGenerateArrayableName(string $attribute, string $expected): void
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

    public function testGenerateInputId(): void
    {
        self::assertSame(
            'namingtest-string',
            Naming::generateInputId('NamingTest', 'string'),
            'Should generate input ID by combining form name and attribute with hyphen.',
        );
    }

    public function testGenerateInputIdWithMultibyteCharacters(): void
    {
        self::assertSame(
            'testform-mąka',
            Naming::generateInputId('TestForm', 'mĄkA'),
            'Should generate input ID with multibyte characters correctly normalized to lowercase.',
        );
    }

    #[DataProviderExternal(NamingProvider::class, 'inputName')]
    public function testGenerateInputName(string $formName, string $attribute, bool $arrayable, string $expected): void
    {
        $name = match ($arrayable) {
            true => Naming::generateInputName($formName, $attribute, true),
            false => Naming::generateInputName($formName, $attribute),
        };

        self::assertSame(
            $expected,
            $name,
            'Should generate input name for given form and attribute according to arrayable flag.',
        );
    }

    public function testGetShortNameClass(): void
    {
        self::assertSame(
            'NamingTest::class',
            Naming::getShortNameClass(self::class),
            'Should return the short name of the class with suffix.',
        );
    }

    public function testGetShortNameClassWithLowercase(): void
    {
        self::assertSame(
            'namingtest',
            Naming::getShortNameClass(self::class, false, true),
            'Should return the short name of the class in lowercase without suffix.',
        );
    }

    public function testGetShortNameClassWithoutSuffix(): void
    {
        self::assertSame(
            'NamingTest',
            Naming::getShortNameClass(self::class, false),
            'Should return the short name of the class without suffix.',
        );
    }

    public function testThrowInvalidArgumentExceptionForEmptyFormModelWithTabularInput(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::FORM_MODEL_NAME_CANNOT_BE_EMPTY->getMessage(),
        );

        Naming::generateInputName('', '[0]dates[0]');
    }

    public function testThrowInvalidArgumentExceptionForInvalidPropertyName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            Message::CANNOT_PARSE_PROPERTY->getMessage('content body'),
        );

        Naming::generateInputName('TestForm', 'content body');
    }

    public function testThrowInvalidArgumentExceptionForRegExpExceedingBacktrackLimit(): void
    {
        $originalLimit = ini_get('pcre.backtrack_limit');

        ini_set('pcre.backtrack_limit', '1');

        try {
            $regexp = '/' . str_repeat('\\x{41}', 1000) . '/';

            $this->expectException(InvalidArgumentException::class);
            $this->expectExceptionMessage(
                Message::INCORRECT_REGEXP->getMessage(),
            );

            Naming::convertToPattern($regexp);
        } finally {
            ini_set('pcre.backtrack_limit', $originalLimit);
        }
    }
}
