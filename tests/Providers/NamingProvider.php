<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

use UIAwesome\Html\Helper\Exception\Message;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\NamingTest} test cases.
 *
 * Provides representative input/output pairs for name generation and regular expression helper methods.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class NamingProvider
{
    /**
     * @phpstan-return array<array{string, string}>
     */
    public static function arrayableName(): array
    {
        return [
            'indexed with multibyte' => [
                '[0]登录[0]',
                '[0]登录[0][]',
            ],
            'multibyte with brackets' => [
                '登录[]',
                '登录[]',
            ],
            'multibyte with indexed brackets' => [
                '登录[0]',
                '登录[0][]',
            ],
            'multibyte without brackets' => [
                '登录',
                '登录[]',
            ],
        ];
    }

    /**
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
     * @phpstan-return array<string, array{string, string|null, string|null}>
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
                '\\u1F596([a-z])',
                '/\\x{1F596}([a-z])/i',
                null,
            ],
        ];
    }

    /**
     * @phpstan-return array<string, array{string, string|null, string}>
     */
    public static function regularExpressionPatternInvalid(): array
    {
        return [
            'incorrect delimiter double slash' => [
                '/.*/i',
                '//',
                Message::INCORRECT_DELIMITER->getMessage(),
            ],
            'incorrect delimiter tilde conflict' => [
                '/~~/i',
                '~~',
                Message::INCORRECT_DELIMITER->getMessage(),
            ],
            'incorrect regexp bad delimiter' => [
                '/.*/i',
                '~',
                Message::INCORRECT_REGEXP->getMessage(),
            ],
            'incorrect regexp dotstar' => [
                '.*',
                null,
                Message::INCORRECT_REGEXP->getMessage(),
            ],
            'incorrect regexp group without delimiters' => [
                '([a-z0-9-]+)',
                null,
                Message::INCORRECT_REGEXP->getMessage(),
            ],
            'incorrect regexp missing end delimiter' => [
                '/.*',
                null,
                Message::INCORRECT_REGEXP->getMessage(),
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
