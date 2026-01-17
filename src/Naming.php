<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * HTML form naming utility for generating input names and identifiers.
 *
 * Provides a concrete implementation that exposes form naming and identifier helper methods.
 *
 * Key features.
 * - Converts a regexp literal to a `pattern` substring via {@see Base\BaseNaming::convertToPattern()}.
 * - Extracts short class names via {@see Base\BaseNaming::getShortNameClass()}.
 * - Generates arrayable names via {@see Base\BaseNaming::generateArrayableName()}.
 * - Generates ids via {@see Base\BaseNaming::generateId()}.
 * - Generates input ids via {@see Base\BaseNaming::generateInputId()}.
 * - Generates input names via {@see Base\BaseNaming::generateInputName()}.
 *
 * Usage example:
 * ```php
 * $name = Naming::generateInputName('User', 'email');
 * // "User[email]"
 *
 * $id = Naming::generateInputId('User', 'email');
 * // "user-email"
 * ```
 *
 * {@see Base\BaseNaming} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Naming extends Base\BaseNaming {}
