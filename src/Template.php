<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

/**
 * HTML template utility for template rendering and token substitution.
 *
 * Provides a concrete implementation that exposes template rendering for token replacement.
 *
 * Key features.
 * - Renders a template string via {@see Base\BaseTemplate::render()}.
 *
 * Note: This helper does NOT perform HTML encoding or XSS sanitization. Ensure all token values are properly encoded
 * before passing them to {@see Base\BaseTemplate::render()}.
 *
 * Usage example:
 * ```php
 * $template = "<label>{label}</label>\n<input value=\"{value}\">";
 * $tokens = [
 *     '{label}' => Encode::content('Email'),
 *     '{value}' => Encode::value('user@example.com'),
 * ];
 *
 * $html = Template::render($template, $tokens);
 * ```
 *
 * {@see Base\BaseTemplate} for the base implementation.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class Template extends Base\BaseTemplate {}
