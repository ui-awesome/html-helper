<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * HTML attribute utility for predictable attribute rendering.
 *
 * Provides a concrete implementation that exposes the attribute rendering and normalization API.
 *
 * Key features.
 * - Normalizes an attribute array via {@see Base\BaseAttributes::normalizeAttributes()}.
 * - Normalizes prefixed keys via {@see Base\BaseAttributes::normalizeKey()}.
 * - Renders an attribute array via {@see Base\BaseAttributes::render()}.
 *
 * Usage example:
 * ```php
 * $attributes = [
 *     'id' => 'login',
 *     'class' => ['form-control', 'is-valid'],
 *     'required' => true,
 *     'data' => ['role' => 'user', 'id' => 42],
 * ];
 *
 * // for programmatic access (example, DOM manipulation)
 * $normalized = Attributes::normalizeAttributes($attributes);
 *
 * // for HTML string output
 * $html = Attributes::render($normalized);
 * // class="form-control is-valid" id="login" required data='{"role":"user","id":42}'
 * ```
 *
 * {@see Base\BaseAttributes} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Attributes extends Base\BaseAttributes {}
