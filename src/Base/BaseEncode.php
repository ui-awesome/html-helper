<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use Stringable;

use function htmlspecialchars;

/**
 * Provides reusable HTML encoding for content and attribute values.
 *
 * {@see htmlspecialchars()} for encoding implementation details.
 *
 * @link https://www.php.net/manual/en/function.htmlspecialchars.php
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseEncode
{
    /**
     * Encodes special characters into HTML entities for use as HTML tag content.
     *
     * Encodes `&`, `<`, and `>` using `ENT_HTML5 | ENT_SUBSTITUTE`.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\Encode::content('<script>alert("XSS")</script>');
     * // &lt;script&gt;alert("XSS")&lt;/script&gt;
     * ```
     *
     * @param string|Stringable $content Content to be encoded for safe HTML output.
     * @param bool $doubleEncode Whether to encode existing entities (default: `true`).
     * @param string $charset Character encoding to use (default: `UTF-8`).
     *
     * @return string Encoded HTML content safe for tag placement.
     *
     * @link https://html.spec.whatwg.org/#attribute-value-(single-quoted)-state
     * @link https://html.spec.whatwg.org/#attribute-value-(double-quoted)-state
     */
    public static function content(string|Stringable $content, bool $doubleEncode = true, string $charset = 'UTF-8'): string
    {
        return htmlspecialchars((string) $content, ENT_HTML5 | ENT_SUBSTITUTE, $charset, $doubleEncode);
    }

    /**
     * Encodes special characters into HTML entities for use as HTML tag quoted attribute values.
     *
     * Encodes `&`, `<`, `>`, `"`, and `'` using `ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES`.
     *
     * Usage example:
     * ```php
     * \UIAwesome\Html\Helper\Encode::value('O\'Reilly & <script>');
     * // O&apos;Reilly &amp; &lt;script&gt;
     * ```
     *
     * @param float|int|string|Stringable|null $value Attribute value to be encoded for safe HTML output.
     * @param bool $doubleEncode Whether to encode existing entities (default: `true`).
     * @param string $charset Character encoding to use (default: `UTF-8`).
     *
     * @return string Encoded HTML attribute value safe for quoted placement.
     *
     * @link https://html.spec.whatwg.org/#attribute-value-(single-quoted)-state
     * @link https://html.spec.whatwg.org/#attribute-value-(double-quoted)-state
     */
    public static function value(
        float|int|string|Stringable|null $value,
        bool $doubleEncode = true,
        string $charset = 'UTF-8',
    ): string {
        return htmlspecialchars((string) $value, ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES, $charset, $doubleEncode);
    }
}
