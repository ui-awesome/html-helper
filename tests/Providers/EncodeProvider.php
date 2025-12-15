<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\EncodeTest} class.
 *
 * Supplies focused datasets used by encoding utilities for content and attribute/value contexts.
 *
 * The cases exercise entity escaping, double-encoding semantics, handling of control characters (including `null`
 * bytes), and conversions of scalar types to string when appropriate.
 *
 * Key features.
 * - Cover numeric and `null` value handling, as well as non-printable characters.
 * - Ensure correct escaping of angle brackets, ampersands and quotes in both content and attribute contexts.
 * - Validate double-encoding behavior for existing HTML entities.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class EncodeProvider
{
    /**
     * Provides datasets for content encoding tests.
     *
     * Each dataset returns the original content, the expected encoded string and a boolean flag indicating whether
     * existing HTML entities should be double-encoded. These cases validate correct escaping in content rendering
     * contexts and handling of special and control characters.
     *
     * @phpstan-return array<string, array{0: string, 1: string, 2: bool}>
     */
    public static function content(): array
    {
        return [
            'ampersand double' => [
                'Sam &amp; Dark',
                'Sam &amp;amp; Dark',
                true,
            ],
            'ampersand no double' => [
                'Sam & Dark',
                'Sam &amp; Dark',
                false,
            ],
            'basic entities double' => [
                "a<>&amp;\"'\x80",
                'a&lt;&gt;&amp;amp;"\'�',
                true,
            ],
            'basic entities no double' => [
                "a<>&\"'\x80",
                'a&lt;&gt;&amp;"\'�',
                false,
            ],
            'quotes not encoded in content' => [
                'He said "Hello" and she said \'Hi\'',
                'He said "Hello" and she said \'Hi\'',
                true,
            ],
            'unicode null double' => [
                '\\u{0000}',
                '\\u{0000}',
                true,
            ],
            'unicode null no double' => [
                '\\u{0000}',
                '\\u{0000}',
                false,
            ],
        ];
    }

    /**
     * Provides datasets for value encoding and general value conversion.
     *
     * Each dataset returns the input value, the expected encoded output, and a boolean flag indicating whether existing
     * entities should be double-encoded. The provider covers complex attribute-safe escaping, numeric and `null`
     * conversions, and preservation of control characters where applicable.
     *
     * @phpstan-return array<string, array{0: mixed, 1: mixed, 2: bool}>
     */
    public static function value(): array
    {
        return [
            'all special chars' => [
                '<a href="test" data-name=\'value\'>A&B</a>',
                '&lt;a href=&quot;test&quot; data-name=&apos;value&apos;&gt;A&amp;B&lt;/a&gt;',
                true,
            ],
            'ampersand double' => [
                'Sam &amp; Dark',
                'Sam &amp;amp; Dark',
                true,
            ],
            'ampersand no double' => [
                'Sam & Dark',
                'Sam &amp; Dark',
                false,
            ],
            'double quote encoding' => [
                'Say "Hello"',
                'Say &quot;Hello&quot;',
                true,
            ],
            'float' => [
                1.5,
                '1.5',
                false,
            ],
            'int' => [
                42,
                '42',
                false,
            ],
            'mixed quotes' => [
                'It\'s a "test"',
                'It\'s a &quot;test&quot;',
                true,
            ],
            'null byte double' => [
                "\0",
                "\0",
                true,
            ],
            'null byte no double' => [
                "\0",
                "\0",
                false,
            ],
            'null' => [
                null,
                '',
                false,
            ],
            'single quote encoding' => [
                "O'Reilly",
                'O&apos;Reilly',
                true,
            ],
            'unicode null double' => [
                "\\u{0000}",
                "\\u{0000}",
                true,
            ],
            'unicode null no double' => [
                "\\u{0000}",
                "\\u{0000}",
                false,
            ],
        ];
    }
}
