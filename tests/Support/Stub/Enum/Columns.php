<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests\Support\Stub\Enum;

/**
 * Enum type representing column count categories for HTML helper and UI component grid testing.
 *
 * Provides a range of standardized column count values (1 through 12) for test scenarios involving CSS class
 * generation, grid layout handling, and component rendering.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
enum Columns: int
{
    /**
     * Eight columns.
     */
    case EIGHT = 8;

    /**
     * Eleven columns.
     */
    case ELEVEN = 11;

    /**
     * Five columns.
     */
    case FIVE = 5;

    /**
     * Four columns.
     */
    case FOUR = 4;

    /**
     * Nine columns.
     */
    case NINE = 9;
    /**
     * One column.
     */
    case ONE = 1;

    /**
     * Seven columns.
     */
    case SEVEN = 7;

    /**
     * Six columns.
     */
    case SIX = 6;

    /**
     * Ten columns.
     */
    case TEN = 10;

    /**
     * Three columns.
     */
    case THREE = 3;

    /**
     * Twelve columns.
     */
    case TWELVE = 12;

    /**
     * Two columns.
     */
    case TWO = 2;
}
