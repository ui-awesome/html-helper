<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseCSSClass;

/**
 * Provides the concrete entry point for CSS class normalization and rendering.
 *
 * Usage example:
 * ```php
 * $attributes = ['id' => 'main'];
 * \UIAwesome\Html\Helper\CSSClass::add($attributes, ['btn', 'btn-primary']);
 * // $attributes['class'] is now "btn btn-primary"
 * ```
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class CSSClass extends BaseCSSClass {}
