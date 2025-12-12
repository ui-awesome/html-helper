<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * Naming utility for form controls and HTML identifiers.
 *
 * Provides a concrete implementation for generation, normalization and validation of HTML form field names, `id`
 * attribute and namespaced naming patterns following HTML conventions and best practices.
 *
 * Designed for integration in form builders, components and view renderers to ensure consistent and collision-resistant
 * identifier generation, predictable mapping between data models and input names, and reliable namespacing for
 * component-scoped forms.
 *
 * Key features.
 * - Deterministic name generation for nested and complex data structures.
 * - Immutable, stateless utilities suitable for reuse across rendering pipelines.
 * - Namespacing helpers for component-level isolation and reuse.
 * - Safe ID generation and normalization compliant with HTML5 identifier constraints.
 * - Type-safe, documented methods for predictable string outputs.
 *
 * {@see Base\BaseNaming} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Naming extends Base\BaseNaming {}
