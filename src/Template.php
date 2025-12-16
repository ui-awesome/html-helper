<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * HTML template utility for advanced, type-safe template rendering and manipulation.
 *
 * Provides a concrete implementation for processing, validating, and rendering HTML templates, supporting dynamic
 * content injection and flexible template composition.
 *
 * Designed for integration in view renderers, tag systems, and asset managers, ensuring consistent and secure handling
 * of template fragments, placeholders, and variable substitution across all supported use cases.
 *
 * Key features.
 * - Dynamic content injection and placeholder replacement for HTML templates.
 * - Standardized output for predictable HTML generation.
 * - Type-safe methods for template composition and fragment management.
 *
 * Note: This helper does NOT perform HTML encoding or XSS sanitization. Ensure all token values are properly encoded
 * before passing them to {@see Base\BaseTemplate::render()}.
 *
 * {@see Base\BaseTemplate} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Template extends Base\BaseTemplate {}
