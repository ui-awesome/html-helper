<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Support\Stub\Enum;

/**
 * Stub enum for test purposes.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
enum AlertType: string
{
    case ERROR = 'error';

    case INFO = 'info';

    case SUCCESS = 'success';

    case WARNING = 'warning';
}
