<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

/**
 * Base class for line break normalization utilities.
 *
 * Collapses repeated line break sequences to a single `\n` while preserving single line breaks.
 *
 * @link https://www.php.net/manual/en/function.preg-replace.php
 * @link https://www.pcre.org/current/doc/html/pcre2pattern.html#SEC6
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseLineBreakNormalizer
{
    /**
     * Collapses two or more consecutive line breaks into a single `\n`.
     *
     * Returns `''` when `$content` is `null` or `''`. Preserves single line breaks, including `\n` and `\r\n`.
     *
     * Usage example:
     * ```php
     * $normalized = \UIAwesome\Html\Helper\LineBreakNormalizer::normalize("Hello\r\n\r\nWorld");
     * // "Hello\nWorld"
     * ```
     *
     * @param string|null $content Text to normalize. Pass `null` or `''` to return `''`.
     *
     * @return string Normalized text with repeated line break groups collapsed.
     */
    public static function normalize(string|null $content): string
    {
        if ($content === null || $content === '') {
            return '';
        }

        return preg_replace('/\R{2,}/', "\n", $content) ?? '';
    }
}
