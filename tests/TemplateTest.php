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

    public function testRenderNormalizesAllLineEndingFormats(): void
    {
        // Test case 1: Windows CRLF (\r\n) should be normalized
        $templateCRLF = "Line 1: {token1}\r\nLine 2: {token2}\r\nLine 3: {token3}";
        $tokens = ['{token1}' => 'A', '{token2}' => 'B', '{token3}' => 'C'];
        $expectedCRLF = 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C';

        self::equalsWithoutLE(
            $expectedCRLF,
            Template::render($templateCRLF, $tokens),
            'CRLF line endings must be normalized correctly',
        );

        // Test case 2: Old Mac CR (\r) should be normalized
        $templateCR = "Line 1: {token1}\rLine 2: {token2}\rLine 3: {token3}";
        $expectedCR = 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C';

        self::equalsWithoutLE(
            $expectedCR,
            Template::render($templateCR, $tokens),
            'CR line endings must be normalized correctly',
        );

        // Test case 3: Unix LF (\n) should work as-is
        $templateLF = "Line 1: {token1}\nLine 2: {token2}\nLine 3: {token3}";
        $expectedLF = 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C';

        self::equalsWithoutLE(
            $expectedLF,
            Template::render($templateLF, $tokens),
            'LF line endings must be handled correctly',
        );

        // Test case 4: Mixed line endings should be normalized
        $templateMixed = "Line 1: {token1}\r\nLine 2: {token2}\nLine 3: {token3}\rLine 4: {token4}";
        $tokensMixed = ['{token1}' => 'A', '{token2}' => 'B', '{token3}' => 'C', '{token4}' => 'D'];
        $expectedMixed = 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C' . PHP_EOL . 'Line 4: D';

        self::equalsWithoutLE(
            $expectedMixed,
            Template::render($templateMixed, $tokensMixed),
            'Mixed line endings must be normalized consistently',
        );

        // Test case 5: Literal '\n' (backslash followed by n) should be converted to actual newline
        $templateLiteral = 'Line 1: {token1}\nLine 2: {token2}';
        $expectedLiteral = 'Line 1: A' . PHP_EOL . 'Line 2: B';

        self::equalsWithoutLE(
            $expectedLiteral,
            Template::render($templateLiteral, $tokens),
            'Literal backslash-n sequences must be converted to actual newlines',
        );

        // Test case 6: Empty lines after token substitution should be filtered
        $templateEmpty = "Line 1: {token1}\n\nLine 2: {token2}\n\n\nLine 3: {token3}";
        $expectedEmpty = 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C';

        self::equalsWithoutLE(
            $expectedEmpty,
            Template::render($templateEmpty, $tokens),
            'Empty lines must be filtered from output',
        );

        // Test case 7: Lines that become empty after token substitution should be filtered
        $templateEmptyAfterSubst = "Line 1: {token1}\n{empty}\nLine 2: {token2}";
        $tokensEmptyAfterSubst = ['{token1}' => 'A', '{empty}' => '', '{token2}' => 'B'];
        $expectedEmptyAfterSubst = 'Line 1: A' . PHP_EOL . 'Line 2: B';

        self::equalsWithoutLE(
            $expectedEmptyAfterSubst,
            Template::render($templateEmptyAfterSubst, $tokensEmptyAfterSubst),
            'Lines that become empty after substitution must be filtered',
        );

        // Test case 8: Combination of CRLF and literal '\n' should both be normalized
        $templateCombined = "Line 1: {token1}\r\nLine 2: {token2}\nLine 3: {token3}";
        $expectedCombined = 'Line 1: A' . PHP_EOL . 'Line 2: B' . PHP_EOL . 'Line 3: C';

        self::equalsWithoutLE(
            $expectedCombined,
            Template::render($templateCombined, $tokens),
            'Combination of CRLF and literal backslash-n must be handled correctly',
        );
    }

    public function testRenderPreventsStrayCRCharacters(): void
    {
        $template = "Header: {title}\r\nContent: {body}\r\nFooter: {footer}";
        $tokens = ['{title}' => 'Test', '{body}' => 'Content', '{footer}' => 'End'];

        $result = Template::render($template, $tokens);

        self::assertStringNotContainsString(
            "\r",
            $result,
            'Output must not contain any carriage return characters',
        );

        $expected = 'Header: Test' . PHP_EOL . 'Content: Content' . PHP_EOL . 'Footer: End';

        self::equalsWithoutLE(
            $expected,
            $result,
            'CRLF templates must produce clean output',
        );
    }

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
