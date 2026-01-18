<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Providers;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\TemplateTest} test cases.
 *
 * Supplies focused datasets used by template helpers to normalize line endings and filter empty lines after token
 * substitution.
 *
 * The cases cover mixed newline sequences (CRLF `\r\n`), (LF `\n`), (CR `\r`), literal backslash-newline (`\n`)
 * handling, and output normalization against `PHP_EOL`.
 *
 * Key features.
 * - Provide datasets covering mixed platform line endings and literal newline escape sequences.
 * - Return structured cases with expected normalized output and an assertion message.
 * - Verify that empty lines are removed both before and after token substitution.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class TemplateProvider
{
    /**
     * @phpstan-return array<
     *   string,
     *   array{template: string, tokens: array<string, string>, expected: string, message: string},
     * >
     */
    public static function lineEndingNormalizationCases(): array
    {
        return [
            'CRLF and literal backslash-n combination' => [
                'template' => "Line 1: {token1}\r\nLine 2: {token2}\nLine 3: {token3}",
                'tokens' => ['{token1}' => 'A', '{token2}' => 'B', '{token3}' => 'C'],
                'expected' => 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C',
                'message' => 'Combination of CRLF and literal backslash-n must be handled correctly',
            ],
            'empty lines filtering' => [
                'template' => "Line 1: {token1}\n\nLine 2: {token2}\n\n\nLine 3: {token3}",
                'tokens' => ['{token1}' => 'A', '{token2}' => 'B', '{token3}' => 'C'],
                'expected' => 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C',
                'message' => 'Empty lines must be filtered from output',
            ],
            'lines empty after substitution filtering' => [
                'template' => "Line 1: {token1}\n{empty}\nLine 2: {token2}",
                'tokens' => ['{token1}' => 'A', '{empty}' => '', '{token2}' => 'B'],
                'expected' => 'Line 1: A' . PHP_EOL . 'Line 2: B',
                'message' => 'Lines that become empty after substitution must be filtered',
            ],
            'literal backslash-n conversion' => [
                'template' => 'Line 1: {token1}\nLine 2: {token2}',
                'tokens' => ['{token1}' => 'A', '{token2}' => 'B'],
                'expected' => 'Line 1: A' . PHP_EOL . 'Line 2: B',
                'message' => 'Literal backslash-n sequences must be converted to actual newlines',
            ],
            'mixed line endings normalization' => [
                'template' => "Line 1: {token1}\r\nLine 2: {token2}\nLine 3: {token3}\rLine 4: {token4}",
                'tokens' => ['{token1}' => 'A', '{token2}' => 'B', '{token3}' => 'C', '{token4}' => 'D'],
                'expected' => 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C' . PHP_EOL . 'Line 4: D',
                'message' => 'Mixed line endings must be normalized consistently',
            ],
            'old Mac CR (\\r) normalization' => [
                'template' => "Line 1: {token1}\rLine 2: {token2}\rLine 3: {token3}",
                'tokens' => ['{token1}' => 'A', '{token2}' => 'B', '{token3}' => 'C'],
                'expected' => 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C',
                'message' => 'CR line endings must be normalized correctly',
            ],
            'unix LF (\\n) handling' => [
                'template' => "Line 1: {token1}\nLine 2: {token2}\nLine 3: {token3}",
                'tokens' => ['{token1}' => 'A', '{token2}' => 'B', '{token3}' => 'C'],
                'expected' => 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C',
                'message' => 'LF line endings must be handled correctly',
            ],
            'windows CRLF (\\r\\n) normalization' => [
                'template' => "Line 1: {token1}\r\nLine 2: {token2}\r\nLine 3: {token3}",
                'tokens' => ['{token1}' => 'A', '{token2}' => 'B', '{token3}' => 'C'],
                'expected' => 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C',
                'message' => 'CRLF line endings must be normalized correctly',
            ],
        ];
    }
}
