<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseLineBreakNormalizer;

/**
 * Line break normalization helper for text content.
 *
 * Usage example:
 * ```php
 * $normalized = \UIAwesome\Html\Helper\LineBreakNormalizer::normalize("Line 1\n\nLine 2");
 * // "Line 1\nLine 2"
 * ```
 */
final class LineBreakNormalizer extends BaseLineBreakNormalizer {}
