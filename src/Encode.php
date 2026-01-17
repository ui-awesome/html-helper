<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * HTML encoding helper for content and attribute output.
 *
 * Provides a stateless API for encoding HTML content and attribute values.
 *
 * The API is designed for integration in view renderers and HTML document workflows, supporting encoding of tag
 * contents, attribute values, and arbitrary strings with configurable charset handling.
 *
 * Key features.
 * - Encodes attribute values via {@see Base\BaseEncode::value()}.
 * - Encodes tag content via {@see Base\BaseEncode::content()}.
 * - Exposes charset and double-encoding control parameters.
 *
 * Usage example:
 * ```php
 * $content = Encode::content('<b>Hello</b>');
 * // "&lt;b&gt;Hello&lt;/b&gt;"
 *
 * $value = Encode::value("O'Reilly & <tag>");
 * // "O&apos;Reilly &amp; &lt;tag&gt;"
 * ```
 *
 * {@see Base\BaseEncode} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Encode extends Base\BaseEncode {}
