<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * CSS class manipulation helper for attribute normalization and merging.
 *
 * Provides a static API for processing, validating and rendering CSS class attributes, supporting string/array
 * conversion, safe class merging, and normalization for HTML output.
 *
 * Designed for integration in HTML helpers, view renderers, tags, and components ensure consistent and secure handling
 * of CSS class attributes across all supported use cases.
 *
 * Key features.
 * - Attribute manipulation and normalization for HTML output.
 * - CSS class name validation and sanitization.
 * - Safe merging and overriding of class lists.
 * - Stateless helpers suitable for reuse.
 * - String and array conversion utilities.
 *
 * {@see Base\BaseCSSClass} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class CSSClass extends Base\BaseCSSClass {}
