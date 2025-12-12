<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Template;
use UIAwesome\Html\Helper\Tests\Support\TestSupport;

/**
 * Test suite for {@see Template} helper functionality and behavior.
 *
 * Validates the rendering and substitution of template tokens with proper string handling.
 *
 * Ensures correct handling, normalization, and validation of template operations, supporting both scalar and array
 * types, as well as empty and missing token values for predictable output.
 *
 * Test coverage.
 * - Accurate rendering of template strings with proper token substitution.
 * - Compatibility with empty and missing token values.
 * - Data-driven validation for edge cases and expected behaviors.
 * - Immutability of the helper's API when rendering templates.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
final class TemplateTest extends TestCase
{
    use TestSupport;

    public function testRenderTemplateWithActualNewlines(): void
    {
        $template = "{{prefix}}\n{{tag}}\n{{suffix}}";
        $tokenValues = [
            '{{prefix}}' => 'prefix',
            '{{tag}}' => '<div>content</div>',
            '{{suffix}}' => '',
        ];

        self::equalsWithoutLE(
            <<<HTML
            prefix
            <div>content</div>
            HTML,
            Template::render($template, $tokenValues),
            'Should render template with actual newline characters.',
        );
    }

    public function testRenderTemplateWithAllTokens(): void
    {
        $template = '{{prefix}}\n{{tag}}\n{{suffix}}';
        $tokenValues = [
            '{{prefix}}' => 'prefix',
            '{{tag}}' => '<div>content</div>',
            '{{suffix}}' => '',
        ];

        self::equalsWithoutLE(
            <<<HTML
            prefix
            <div>content</div>
            HTML,
            Template::render($template, $tokenValues),
            'Should render template with all tokens provided.',
        );
    }

    public function testRenderTemplateWithEmptyOrMissingTokens(): void
    {
        $template = '{{prefix}}\n{{tag}}\n{{suffix}}';
        $tokenValues = [
            '{{prefix}}' => '',
            '{{label}}' => '',
            '{{tag}}' => '<div>content</div>',
            '{{suffix}}' => 'suffix',
        ];

        self::equalsWithoutLE(
            <<<HTML
            <div>content</div>
            suffix
            HTML,
            Template::render($template, $tokenValues),
            'Should render template with empty or missing tokens.',
        );
    }
}
