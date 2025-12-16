<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Support\Stub\Enum;

/**
 * Enum type representing status values for testing enum utilities and workflow logic.
 *
 * Provides standardized status values for test scenarios involving state transitions, validation, and enum
 * normalization.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
enum Status: string
{
    /**
     * Active status value.
     */
    case ACTIVE = 'active';

    /**
     * Inactive status value.
     */
    case INACTIVE = 'inactive';
}
