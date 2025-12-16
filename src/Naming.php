<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * HTML form naming utility for generating consistent input names and identifiers.
 *
 * Provides a concrete implementation for producing predictable, standards-compliant HTML form input `name` and `id`
 * values, parsing complex property notation (including tabular and nested inputs), and converting regular expression
 * literals to pattern substrings suitable for client-side validation.
 *
 * Designed for integration in form builders, tag renderers and view helpers, ensuring consistent naming and identifier
 * generation across all supported use cases.
 *
 * Key features.
 * - Conversion of regular expression literals to pattern substrings suitable for the `pattern` attribute.
 * - Generation of arrayable input names and unique identifiers compatible with HTML form conventions.
 * - Parsing of property expressions into name, prefix and suffix components for tabular and nested inputs.
 *
 * {@see Base\BaseNaming} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Naming extends Base\BaseNaming {}
