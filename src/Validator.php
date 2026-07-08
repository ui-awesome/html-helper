<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseValidator;

/**
 * Provides the concrete entry point for validation helpers.
 *
 * Usage example:
 * ```php
 * if (\UIAwesome\Html\Helper\Validator::intLike('42', 0, 100) === false) {
 *     throw new InvalidArgumentException('Invalid page size.');
 * }
 * \UIAwesome\Html\Helper\Validator::oneOf('red', ['red', 'green', 'blue'], 'color');
 * ```
 */
final class Validator extends BaseValidator {}
