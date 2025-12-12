<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

use UIAwesome\Html\Helper\Exception\Message;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\NamingTest} class.
 *
 * Supplies structured test data for name and regular expression utilities used when rendering form inputs and
 * validating naming conventions in HTML contexts. The provider focuses on arrayable name generation, input name
 * assembly with optional prefixes and array-notation handling, and regular expression pattern normalization and
 * validation.
 *
 * The test data covers edge cases for multibyte characters, explicit numeric indices, empty prefixes, and delimiter
 * handling for regular expressions. Each dataset is named for clear identification during test execution and debugging.
 *
 * Key features.
 * - Ensures correct assembly of arrayable input names for forms, including multibyte and indexed keys.
 * - Provides canonical and invalid regular expression patterns with expected delimiters and error messages.
 * - Validates construction of input names with and without form prefixing and arrayable flag.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class NamingProvider
{
    /**
     * Provides test cases for generation of arrayable input names.
     *
     * Supplies pairs of original input names and their expected arrayable forms. Covers multibyte keys, existing array
     * notation, indexed names and nested bracketed prefixes to ensure correct canonical transformation when inputs are
     * marked as arrayable.
     *
     * @return array Test cases mapping original name to arrayable name.
     *
     * @phpstan-return array<array{string, string}>
     */
    public static function arrayableName(): array
    {
        return [
            [
                '登录',
                '登录[]',
            ],
            [
                '登录[]',
                '登录[]',
            ],
            [
                '登录[0]',
                '登录[0][]',
            ],
            [
                '[0]登录[0]',
                '[0]登录[0][]',
            ],
        ];
    }

    /**
     * Provides test cases for assembly of input names with optional form prefix and arrayable flag.
     *
     * Each test case returns a tuple: [formPrefix, name, arrayable, expected]. The data covers cases with empty prefix,
     * multibyte names, explicit numeric indices, and both arrayable and non-arrayable modes. This ensures consistent
     * name concatenation logic for form rendering utilities.
     *
     * @return array Test cases for input name assembly.
     *
     * @phpstan-return array<string, array{string, string, bool, string}>
     */
    public static function inputName(): array
    {
        return [
            'arrayable no prefix value age' => [
                '',
                'age',
                true,
                'age[]',
            ],
            'arrayable no prefix value dates[0]' => [
                '',
                'dates[0]',
                true,
                'dates[0][]',
            ],
            'arrayable with value [0]dates[0]' => [
                'TestForm',
                '[0]dates[0]',
                true,
                'TestForm[0][dates][0][]',
            ],
            'arrayable with value age' => [
                'TestForm',
                'age',
                true,
                'TestForm[age][]',
            ],
            'arrayable with value content' => [
                'TestForm',
                'content',
                true,
                'TestForm[content][]',
            ],
            'arrayable with value dates[0]' => [
                'TestForm',
                'dates[0]',
                true,
                'TestForm[dates][0][]',
            ],
            'arrayable with value multibyte' => [
                'TestForm',
                'mĄkA',
                true,
                'TestForm[mĄkA][]',
            ],
            'multibyte' => [
                'TestForm',
                'mĄkA',
                false,
                'TestForm[mĄkA]',
            ],
            'value [0]content' => [
                'TestForm',
                '[0]content',
                false,
                'TestForm[0][content]',
            ],
            'value [0]dates[0]' => [
                'TestForm',
                '[0]dates[0]',
                false,
                'TestForm[0][dates][0]',
            ],
            'value age' => [
                'TestForm',
                'age',
                false,
                'TestForm[age]',
            ],
            'value dates[0]' => [
                'TestForm',
                'dates[0]',
                false,
                'TestForm[dates][0]',
            ],
            'value no prefix dates[0]' => [
                '',
                'dates[0]',
                false,
                'dates[0]',
            ],
            'value no prefix value age' => [
                '',
                'age',
                false,
                'age',
            ],
        ];
    }

    /**
     * Provides canonical regular expression patterns and their expected delimited forms.
     *
     * Supplies tuples of [pattern, expectedDelimitedPattern, explicitDelimiter]. The provider includes empty patterns,
     * patterns requiring explicit custom delimiters, and Unicode-aware conversions used by the library's regexp
     * utilities.
     *
     * @return array Test cases for regular expression pattern normalization.
     *
     * @phpstan-return array<string, array{string, string, string|null}>
     */
    public static function regularExpressionPattern(): array
    {
        return [
            'dot_star' => [
                '.*',
                '/.*/',
                null,
            ],
            'empty_pattern' => [
                '',
                '//',
                null,
            ],
            'group_with_custom_delimiter' => [
                '([a-z0-9-]+)',
                '~([a-z0-9-]+)~Ugimex',
                '~',
            ],
            'group_with_flags_slash' => [
                '([a-z0-9-]+)',
                '/([a-z0-9-]+)/Ugimex',
                null,
            ],
            'group_with_flags_tilde' => [
                '([a-z0-9-]+)',
                '~([a-z0-9-]+)~Ugimex',
                null,
            ],
            'unicode_emoji' => [
                '\u1F596([a-z])',
                '/\x{1F596}([a-z])/i',
                null,
            ],
        ];
    }

    /**
     * Provides invalid regular expression patterns and the expected error messages.
     *
     * Each test case includes [inputPattern, delimiterCandidate, expectedErrorMessage]. These cases exercise delimiter
     * detection, malformed patterns, missing end delimiters, and minimum-length constraints. The expected messages are
     * produced by the library's {@see Message} enum and reflect precise validation failures.
     *
     * @return array Test cases for invalid regular expression patterns and corresponding error messages.
     *
     * @phpstan-return array<string, array{string, string|null, string}>
     */
    public static function regularExpressionPatternInvalid(): array
    {
        return [
            'incorrect delimiter double slash' => [
                '/.*/i',
                '//',
                Message::INCORRET_DELIMITER->getMessage(),
            ],
            'incorrect delimiter tilde conflict' => [
                '/~~/i',
                '~~',
                Message::INCORRET_DELIMITER->getMessage(),
            ],
            'incorrect regexp bad delimiter' => [
                '/.*/i',
                '~',
                Message::INCORRET_REGEXP->getMessage(),
            ],
            'incorrect regexp dotstar' => [
                '.*',
                null,
                Message::INCORRET_REGEXP->getMessage(),
            ],
            'incorrect regexp group without delimiters' => [
                '([a-z0-9-]+)',
                null,
                Message::INCORRET_REGEXP->getMessage(),
            ],
            'incorrect regexp missing end delimiter' => [
                '/.*',
                null,
                Message::INCORRET_REGEXP->getMessage(),
            ],
            'length less than two empty' => [
                '',
                null,
                Message::LENGTH_LESS_THAN_TWO->getMessage(),
            ],
            'length less than two star' => [
                '*',
                null,
                Message::LENGTH_LESS_THAN_TWO->getMessage(),
            ],
        ];
    }
}
