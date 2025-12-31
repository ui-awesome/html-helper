<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * Validation utility for common HTML helper values and configuration.
 *
 * Provides a concrete implementation for validating values commonly used in HTML attribute rendering and helper
 * configuration, including integer-like inputs and strict allow-list membership checks.
 *
 * Designed for integration in tag renderers, view systems, and helper components requiring predictable validation
 * behavior with explicit exceptions for invalid values.
 *
 * Key features.
 * - Allow-list validation with UnitEnum normalization for consistent, strict comparisons.
 * - Integer-like validation for int and integer strings with optional range constraints.
 * - Positive-like number validation for int, float, and numeric strings with optional max constraint.
 * - Predictable behavior with explicit exceptions for invalid values.
 *
 * {@see Base\BaseValidator} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Validator extends Base\BaseValidator {}
