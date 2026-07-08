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
 */
final class Naming extends BaseNaming {}
