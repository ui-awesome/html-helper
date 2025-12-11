<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Support\Stub\Enum;

/**
 * Enum type representing alert categories for testing HTML helper functionality.
 *
 * Provides a set of standardized alert types for use in test scenarios involving CSS class generation, attribute
 * handling, and component rendering.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
enum AlertType: string
{
    /**
     * Type representing an error condition.
     */
    case ERROR = 'error';

    /**
     * Type representing informational messages.
     */
    case INFO = 'info';

    /**
     * Type representing success messages.
     */
    case SUCCESS = 'success';

    /**
     * Type representing warning messages.
     */
    case WARNING = 'warning';
}
