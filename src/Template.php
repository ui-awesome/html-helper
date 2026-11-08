<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseTemplate;

/**
 * Provides the concrete entry point for template token substitution.
 *
 * This helper does not perform HTML encoding. Encode token values before rendering.
 */
final class Template extends BaseTemplate {}
