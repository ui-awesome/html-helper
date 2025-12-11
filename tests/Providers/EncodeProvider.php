<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\EncodeTest} class.
 *
 * Supplies comprehensive test data for validating HTML entity encoding, double encoding behavior, and Unicode/binary
 * sequence handling, ensuring standards-compliant output and security against XSS vulnerabilities.
 *
 * The test data covers real-world scenarios for encoding HTML content and attribute values, supporting various input
 * types such as strings, integers, floats, and `null`. It ensures consistent behavior for double encoding and special
 * character handling across different encoding modes.
 *
 * The provider organizes test cases with descriptive names for clear identification of failure cases during test
 * execution and debugging sessions.
 *
 * Key features.
 * - Covers HTML entity encoding, including special characters and Unicode sequences.
 * - Named test data sets for precise failure identification.
 * - Validation of double encoding behavior and handling of mixed input types.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class EncodeProvider
{
    /**
     * Provides test cases for HTML encoding scenarios.
     *
     * Supplies test data for validating HTML entity encoding, double encoding behavior, and Unicode/binary sequence
     * handling.
     *
     * Each test case includes the input string, the expected encoded output, and a flag indicating whether double
     * encoding is enabled.
     *
     * @return array Test data for encoding scenarios.
     *
     * @phpstan-return array<string, array{string, string, bool}>
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
                '\u{0000}',
                '\u{0000}',
                true,
            ],
            'unicode null no double' => [
                '\u{0000}',
                '\u{0000}',
                false,
            ],
        ];
    }

    /**
     * Provides test cases for encode value scenarios.
     *
     * Supplies test data for validating mixed type encoding (`int`, `float`, `null`, `string`), double encoding
     * behavior, and Unicode/binary sequence handling.
     *
     * Each test case includes the input value, the expected encoded output, and a flag indicating whether double
     * encoding is enabled.
     *
     * @return array Test data for encode value scenarios.
     *
     * @phpstan-return array<string, array{mixed, mixed, bool}>
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
                'It&apos;s a &quot;test&quot;',
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
                "\u{0000}",
                "\u{0000}",
                true,
            ],
            'unicode null no double' => [
                "\u{0000}",
                "\u{0000}",
                false,
            ],
        ];
    }
}
