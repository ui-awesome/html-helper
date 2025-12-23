<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Support\Stub\Enum;

/**
 * Enum type representing priority levels for testing workflow and sorting logic.
 *
 * Provides standardized priority values for test scenarios involving task ordering, queue management, and
 * priority-based branching.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
enum Priority: int
{
    /**
     * High-priority value.
     */
    case HIGH = 2;

    /**
     * Low-priority value.
     */
    case LOW = 1;
}
