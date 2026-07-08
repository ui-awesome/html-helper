<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper;

use UIAwesome\Html\Helper\Base\BaseTemplate;

/**
 * Provides the concrete entry point for template token substitution.
 *
 * This helper does not perform HTML encoding. Encode token values before rendering.
 *
 * Usage example:
 * ```php
 * $template = "<label>{label}</label>\n<input value=\"{value}\">";
 * $tokens = [
 *     '{label}' => \UIAwesome\Html\Helper\Encode::content('Email'),
 *     '{value}' => \UIAwesome\Html\Helper\Encode::value('user@example.com'),
 * ];
 *
 * $html = \UIAwesome\Html\Helper\Template::render($template, $tokens);
 * ```
 */
final class Template extends BaseTemplate {}
