<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseNaming;

/**
 * Provides the concrete entry point for form naming and identifier helpers.
 *
 * Usage example:
 * ```php
 * $name = \UIAwesome\Html\Helper\Naming::generateInputName('User', 'email');
 * // "User[email]"
 * $id = \UIAwesome\Html\Helper\Naming::generateInputId('User', 'email');
 * // "user-email"
 * ```
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Naming extends BaseNaming {}
