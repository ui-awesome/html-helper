<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseCSSClass;

/**
 * CSS class utility for normalization, validation, and rendering.
 *
 * Provides a concrete implementation that exposes the CSS class API for attribute arrays.
 *
 * Key features.
 * - Adds classes to an attribute array via {@see Base\BaseCSSClass::add()}.
 * - Renders a class string with allow-list validation via {@see Base\BaseCSSClass::render()}.
 *
 * Usage example:
 * ```php
 * $attributes = ['id' => 'main'];
 *
 * CSSClass::add($attributes, ['btn', 'btn-primary']);
 * // $attributes['class'] is now "btn btn-primary"
 * ```
 *
 * {@see BaseCSSClass} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class CSSClass extends BaseCSSClass {}
