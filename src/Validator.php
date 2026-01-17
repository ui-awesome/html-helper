<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * Validation utility for common HTML helper values and configuration.
 *
 * Provides a concrete implementation that exposes validation helpers.
 *
 * Key features.
 * - Validates allow-list membership via {@see Base\BaseValidator::oneOf()}.
 * - Validates integer-like values via {@see Base\BaseValidator::intLike()}.
 * - Validates non-negative numeric values via {@see Base\BaseValidator::positiveLike()}.
 *
 * Usage example:
 * ```php
 * if (Validator::intLike('42', 0, 100) === false) {
 *     throw new InvalidArgumentException('Invalid page size.');
 * }
 *
 * Validator::oneOf('red', ['red', 'green', 'blue'], 'color');
 * ```
 *
 * {@see Base\BaseValidator} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Validator extends Base\BaseValidator {}
