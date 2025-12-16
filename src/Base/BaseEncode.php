<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use function htmlspecialchars;

/**
 * Base class for HTML encoding utilities for safe content and attribute value generation.
 *
 * Provides a unified API for encoding HTML content and attribute values, ensuring that special characters are properly
 * escaped to prevent XSS vulnerabilities and ensure valid HTML output.
 *
 * This class is designed for integration in HTML helpers, tag builders, and view renderers, supporting charset
 * configuration, double-encoding control, and HTML5-compliant output. It enables correct encoding for tag content,
 * quoted attribute values, and other scenarios where HTML entity escaping is required.
 *
 * Key features.
 * - Charset flexibility for internationalization and encoding safety.
 * - Double-encode control for idempotent output.
 * - HTML5-compliant output for modern browsers.
 * - Immutable, stateless design for safe reuse.
 * - Type-safe, static encoding methods for content and attributes.
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
     * Converts the characters `&`, `<`, and `>` into their corresponding HTML entities to ensure safe rendering of
     * dynamic content within HTML tags, for example, `<div>tag content</div>`.
     *
     * It supports configurable character encoding and double-encoding control for idempotent and internationalized
     * output.
     *
     * @param string $content Content to be encoded for safe HTML output.
     * @param bool $doubleEncode Whether to encode existing entities (default: `true`).
     * @param string $charset Character encoding to use (default: `UTF-8`).
     *
     * @return string Encoded HTML content safe for tag placement.
     *
     * @link https://html.spec.whatwg.org/#attribute-value-(single-quoted)-state
     * @link https://html.spec.whatwg.org/#attribute-value-(double-quoted)-state
     *
     * Usage example:
     * ```php
     * Encode::content('<script>alert("XSS")</script>');
     * // &lt;script&gt;alert("XSS")&lt;/script&gt;
     * ```
     */
    public static function content(string $content, bool $doubleEncode = true, string $charset = 'UTF-8'): string
    {
        return htmlspecialchars($content, ENT_HTML5 | ENT_SUBSTITUTE, $charset, $doubleEncode);
    }

    /**
     * Encodes special characters into HTML entities for use as HTML tag quoted attribute values.
     *
     * Converts the characters `&`, `<`, `>`, `"`, `'`, and the null character (`U+0000`) into their corresponding HTML
     * entities to ensure safe rendering of dynamic attribute values within HTML tags, such as
     * `<input value="my-value">`.
     *
     * It supports configurable character encoding and double-encoding control for idempotent and internationalized
     * output, following the HTML5 specification for attribute value encoding.
     *
     * @param float|int|string|null $value Attribute value to be encoded for safe HTML output.
     * @param bool $doubleEncode Whether to encode existing entities (default: `true`).
     * @param string $charset Character encoding to use (default: `UTF-8`).
     *
     * @return string Encoded HTML attribute value safe for quoted placement.
     *
     * @link https://html.spec.whatwg.org/#attribute-value-(single-quoted)-state
     * @link https://html.spec.whatwg.org/#attribute-value-(double-quoted)-state
     *
     * Usage example:
     * ```php
     * Encode::value('O\'Reilly & <script>');
     * // O&apos;Reilly &amp; &lt;script&gt;
     * ```
     */
    public static function value(
        float|int|string|null $value,
        bool $doubleEncode = true,
        string $charset = 'UTF-8',
    ): string {
        return htmlspecialchars((string) $value, ENT_HTML5 | ENT_SUBSTITUTE | ENT_QUOTES, $charset, $doubleEncode);
    }
}
