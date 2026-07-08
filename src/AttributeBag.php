<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseAttributeBag;

/**
 * Provides the concrete entry point for HTML attribute bag operations.
 *
 * Usage example:
 * ```php
 * \UIAwesome\Html\Helper\AttributeBag::set($attributes, 'disabled', true);
 * \UIAwesome\Html\Helper\AttributeBag::get($attributes, 'id');
 * ```
 */
final class AttributeBag extends BaseAttributeBag {}
