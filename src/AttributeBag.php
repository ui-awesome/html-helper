<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseAttributeBag;

/**
 * Provides the concrete entry point for HTML attribute bag operations.
 *
 * Usage example:
 * ```php
 * \UIAwesome\Html\Helper\AttributeBag::add($attributes, 'disabled', true);
 * \UIAwesome\Html\Helper\AttributeBag::get($attributes, 'id');
 * ```
 *
 * @copyright Copyright (C) 2026 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class AttributeBag extends BaseAttributeBag {}
