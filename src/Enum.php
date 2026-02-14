<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseEnum;

/**
 * Provides the concrete entry point for enum normalization helpers.
 *
 * Usage example:
 * ```php
 * $values = \UIAwesome\Html\Helper\Enum::normalizeArray(['active', 'inactive', 'raw']);
 * // ['active', 'inactive', 'raw']
 * $value = \UIAwesome\Html\Helper\Enum::normalizeValue('active');
 * // 'active'
 * ```
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Enum extends BaseEnum {}
