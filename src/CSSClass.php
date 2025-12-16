<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * CSS class utility for normalization, validation, and rendering.
 *
 * Provides a concrete implementation for working with the HTML `class` attribute, supporting common inputs such as a
 * string, an array, UnitEnum values, or `null` and producing predictable output suitable for HTML rendering.
 *
 * Designed for integration in HTML attribute handling, tag renderers, and view helpers, ensuring consistent and safe
 * manipulation of CSS class lists across all supported use cases.
 *
 * Key features.
 * - Merging behavior for existing `class` attributes with uniqueness preservation, with explicit override support.
 * - Normalization of string, array, and UnitEnum inputs into a consistent class list.
 * - Rendering helpers that validate against an explicit allow-list.
 * - Validation of class names using a strict regular expression.
 *
 * {@see Base\BaseCSSClass} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class CSSClass extends Base\BaseCSSClass {}
