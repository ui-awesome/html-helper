<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Support\Stub\Enum;

/**
 * Enum type representing HTML attribute keys for testing normalization logic.
 *
 * Provides standardized attribute keys for test scenarios involving HTML attribute rendering and normalization.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
enum Key: string
{
    /**
     * Key representing the 'aria-label' HTML attribute.
     */
    case ARIA_LABEL = 'aria-label';

    /**
     * Key representing the 'data-toggle' HTML attribute.
     */
    case DATA_TOGGLE = 'data-toggle';

    /**
     * Key representing the 'onclick' HTML attribute.
     */
    case ON_CLICK = 'onclick';
}
