<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * HTML attribute utility for standardized, safe attribute rendering.
 *
 * Provides a concrete implementation for rendering HTML attributes in a predictable, standards-compliant order,
 * supporting common HTML5 use cases such as boolean attributes, `class` and `style` composition, and `data-*` /
 * `aria-*` attribute expansion.
 *
 * Designed for integration in tag renderers and view helpers, ensuring consistent encoding, validation, and output
 * formatting of attribute strings across all supported use cases.
 *
 * Key features.
 * - Array handling for `class`, `style`, `data-*`, and `aria-*` attributes.
 * - Attribute sorting by priority for readable, maintainable HTML.
 * - JSON encoding for complex attribute values.
 * - Support for boolean attributes (for example, `checked`, `disabled`).
 * - Validation of attribute names using a strict regex pattern.
 *
 * {@see Base\BaseAttributes} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Attributes extends Base\BaseAttributes {}
