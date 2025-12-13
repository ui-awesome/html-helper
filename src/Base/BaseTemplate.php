<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use function explode;
use function implode;
use function str_replace;
use function strtr;

/**
 * Base class for template string rendering and token substitution in HTML helpers.
 *
 * Provides a unified API for rendering template strings with dynamic token replacement, supporting safe and predictable
 * output for HTML tag generation, attribute rendering, and view systems.
 *
 * This class is designed for use in HTML helpers, tag builders, and view renderers, enabling flexible and secure
 * template string processing for modern web applications.
 *
 * Key features.
 * - Efficient token substitution for template-based rendering.
 * - Immutable, stateless design for safe reuse.
 * - Integration-ready for tag, attribute, and view rendering systems.
 * - Type-safe, static rendering methods for template strings.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseTemplate
{
    /**
     * Renders a template string by substituting tokens with provided values.
     *
     * Processes the given template string, replacing tokens according to the provided associative array and returns
     * the rendered result as a string with lines joined by the system line ending.
     *
     * Note: Literal `\n` sequences (backslash followed by 'n') in the template will be converted to actual newline
     * characters before processing. **Empty lines after token substitution are filtered out.**
     *
     * @param string $template Template string containing tokens to be replaced.
     * @param array $tokenValues Associative array of token replacements.
     *
     * @return string Rendered template string with substituted values.
     *
     * @phpstan-param mixed[] $tokenValues
     *
     * Usage example:
     * ```php
     * $template = "Hello, {name}!\nWelcome to {site}.";
     * $tokens = ['{name}' => 'Alice', '{site}' => 'Example.com'];
     * $result = Template::render($template, $tokens);
     * // "Hello, Alice!
     * // Welcome to Example.com."
     * ```
     */
    public static function render(string $template, array $tokenValues): string
    {
        $template = str_replace('\n', "\n", $template);
        $lines = explode("\n", $template);

        $results = [];

        foreach ($lines as $line) {
            $value = strtr($line, $tokenValues);

            if ($value !== '') {
                $results[] = $value;
            }
        }

        return implode(PHP_EOL, $results);
    }
}
