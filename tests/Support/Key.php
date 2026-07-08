<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Support;

/**
 * Stub string-backed enum used by test fixtures.
 */
enum Key: string
{
    case ARIA_LABEL = 'aria-label';

    case DATA_TOGGLE = 'data-toggle';

    case ON_CLICK = 'onclick';
}
