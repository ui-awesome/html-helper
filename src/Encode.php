<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseEncode;

/**
 * Provides the concrete entry point for HTML content and attribute encoding.
 *
 * Usage example:
 * ```php
 * $content = \UIAwesome\Html\Helper\Encode::content('<b>Hello</b>');
 * // "&lt;b&gt;Hello&lt;/b&gt;"
 * $value = \UIAwesome\Html\Helper\Encode::value("O'Reilly & <tag>");
 * // "O&apos;Reilly &amp; &lt;tag&gt;"
 * ```
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Encode extends BaseEncode {}
