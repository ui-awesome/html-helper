<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseAttributes;

/**
 * Provides the concrete entry point for HTML attribute normalization and rendering.
 *
 * Usage example:
 * ```php
 * $attributes = [
 *     'class' => ['form-control', 'is-valid'],
 *     'data' => ['role' => 'user', 'id' => 42],
 *     'id' => 'login',
 *     'required' => true,
 * ];
 * $normalized = \UIAwesome\Html\Helper\Attributes::normalizeAttributes($attributes);
 * $html = \UIAwesome\Html\Helper\Attributes::render($normalized);
 * // class="form-control is-valid" id="login" required data='{"role":"user","id":42}'
 * ```
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Attributes extends BaseAttributes {}
