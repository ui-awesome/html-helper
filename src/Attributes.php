<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * HTML attribute helper for safe, flexible attribute rendering and manipulation.
 *
 * Provides a fluent, immutable API for processing and rendering HTML attributes, supporting array and boolean values,
 * data/ARIA attribute expansion and HTML-safe encoding for secure output.
 *
 * Designed for integration in view renderers, tags and components, it ensures correct attribute ordering, escaping and
 * compatibility with all major HTML5 use cases.
 *
 * Key features.
 * - Array and boolean attribute handling for dynamic attribute sets.
 * - Data/ARIA attribute expansion for accessibility and custom data.
 * - HTML-safe encoding to prevent XSS and markup errors.
 * - Immutable, tag-based configuration for safe reuse.
 * - Standardized attribute ordering for predictable output.
 * - Type-safe, documented methods for all major attribute scenarios.
 *
 * The API is intended for use in advanced HTML generation workflows, including asset managers, tags and server-side
 * rendering engines, where attribute correctness and security are critical.
 *
 * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Attributes
 * {@see Base\BaseAttributes} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Attributes extends Base\BaseAttributes {}
