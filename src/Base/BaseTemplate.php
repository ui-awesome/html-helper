<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Base;

use function explode;
use function implode;
use function str_replace;
use function strtr;

/**
 * Provides reusable token substitution for template strings.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
abstract class BaseTemplate
{
    /**
     * Renders a template string by substituting tokens with provided values.
     *
     * Converts literal `\n` sequences to line breaks and removes empty lines after substitution.
     *
     * Usage example:
     * ```php
     * $template = "Hello, {name}!\nWelcome to {site}.";
     * $tokens = ['{name}' => 'Alice', '{site}' => 'Example.com'];
     * \UIAwesome\Html\Helper\Template::render($template, $tokens);
     * // "Hello, Alice!\nWelcome to Example.com."
     * ```
     *
     * @param string $template Template string containing tokens to be replaced.
     * @param array $tokenValues Associative array of token replacements.
     *
     * @return string Rendered template string with substituted values.
     *
     * @phpstan-param mixed[] $tokenValues
     */
    public static function render(string $template, array $tokenValues): string
    {
        $template = self::normalizeLineEndings($template);
        $lines = explode("\n", $template);

        $results = [];

        foreach ($lines as $line) {
            $value = strtr($line, $tokenValues);

            if ($value !== '') {
                $results[] = $value;
            }
        }

        return implode("\n", $results);
    }

    /**
     * Normalizes all line-ending formats to Unix LF (`\n`).
     *
     * Converts `\r\n`, `\r`, and literal `\n` sequences to actual `\n` line breaks.
     *
     * @param string $template Template string with potentially mixed line endings.
     *
     * @return string Template with normalized line endings.
     *
     * @infection-ignore-all Line ending normalization cannot be reliably mutation-tested on Windows due to
     * platform-specific behavior. Manual testing and integration tests on Unix/Linux CI environments provide coverage
     * for this logic.
     */
    private static function normalizeLineEndings(string $template): string
    {
        // first normalize actual CRLF and CR to LF
        $template = str_replace(["\r\n", "\r"], "\n", $template);

        // then convert literal `\n` sequences to actual newlines
        return str_replace('\n', "\n", $template);
    }
}
