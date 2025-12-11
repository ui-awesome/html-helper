<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * Validation utility helper for robust, type-safe validation of HTML attributes and values.
 *
 * Provides a concrete, ready-to-use implementation for validating and processing attribute values, supporting advanced
 * validation scenarios, strict type safety, and standardized error handling for invalid input.
 *
 * Designed for integration in view renderers, tag systems, and component engines, ensuring consistent and secure
 * validation of attribute sets, values, and custom rules across all supported use cases.
 *
 * Key features.
 * - Centralized error handling and reporting for invalid input.
 * - Standardized output for predictable validation results.
 * - Stateless, reusable helpers for validation workflows.
 * - Type-safe validation for HTML attributes and values.
 *
 * {@see Base\BaseValidator} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Validator extends Base\BaseValidator {}
