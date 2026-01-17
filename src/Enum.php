<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * Enum utility helper for enum normalization and value extraction.
 *
 * Provides a concrete, ready-to-use implementation for working with PHP enums, offering utility methods for value
 * extraction and conversion between enum cases and scalar values.
 *
 * This class centralizes common enum operations, providing consistent handling of enum values and explicit
 * exceptions for invalid input.
 *
 * Key features.
 * - Converts backed enums to their scalar `value` and pure enums to `name`.
 * - Normalizes arrays of mixed values via {@see Base\BaseEnum::normalizeArray()}.
 * - Normalizes single values via {@see Base\BaseEnum::normalizeValue()}.
 *
 * Usage example:
 * ```php
 * $values = Enum::normalizeArray(['active', 'inactive', 'raw']);
 * // ['active', 'inactive', 'raw']
 *
 * $value = Enum::normalizeValue('active');
 * // 'active'
 * ```
 *
 * {@see Base\BaseEnum} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Enum extends Base\BaseEnum {}
