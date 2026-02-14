<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Provider;

/**
 * Data provider for {@see \UIAwesome\Html\Helper\Tests\LineBreakNormalizerTest} test cases.
 *
 * Provides representative input/output pairs for line break normalization.
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class LineBreakNormalizerProvider
{
    /**
     * @phpstan-return array<string, array{string|null, string}>
     */
    public static function normalize(): array
    {
        return [
            'empty string' => [
                '',
                '',
            ],
            'leading double LF collapsed' => [
                "\n\nHello",
                "\nHello",
            ],
            'mixed CRLF and LF becomes single LF' => [
                "Hello\r\n\nWorld",
                "Hello\nWorld",
            ],
            'mixed repeated LF blocks' => [
                "A\n\nB\n\nC",
                "A\nB\nC",
            ],
            'null content' => [
                null,
                '',
            ],
            'plain text' => [
                'Hello World',
                'Hello World',
            ],
            'preserves single CR line break' => [
                "Hello\rWorld",
                "Hello\rWorld",
            ],
            'preserves single CRLF line break' => [
                "Hello\r\nWorld",
                "Hello\r\nWorld",
            ],
            'preserves single LF line break' => [
                "Hello\nWorld",
                "Hello\nWorld",
            ],
            'repeated CRLF becomes LF' => [
                "Hello\r\n\r\nWorld",
                "Hello\nWorld",
            ],
            'repeated LF becomes single LF' => [
                "Hello\n\nWorld",
                "Hello\nWorld",
            ],
            'trailing double LF collapsed' => [
                "Hello\n\n",
                "Hello\n",
            ],
            'triple LF becomes single LF' => [
                "Hello\n\n\nWorld",
                "Hello\nWorld",
            ],
        ];
    }
}
