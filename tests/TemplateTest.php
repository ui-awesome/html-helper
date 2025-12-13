<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Template;
use UIAwesome\Html\Helper\Tests\Providers\TemplateProvider;
use UIAwesome\Html\Helper\Tests\Support\TestSupport;

/**
 * Test suite for {@see Template} helper functionality and behavior.
 *
 * Validates template rendering, token replacement, and normalization of line endings to ensure deterministic and secure
 * output for HTML fragments and lightweight components.
 *
 * Ensures correct handling of multiple line ending formats, removal of stray carriage return characters, and
 * predictable rendering when tokens are provided, empty, or missing.
 *
 * Test coverage.
 * - Correct rendering with actual newline characters and with empty or absent tokens.
 * - Elimination of stray CR characters from rendered output.
 * - Integration tests for token replacement semantics and safety of rendered content.
 * - Normalization of CRLF, CR, and LF into the platform line ending.
 *
 * {@see TemplateProvider} for data-driven test cases and edge conditions.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helpers')]
final class TemplateTest extends TestCase
{
    use TestSupport;

    /**
     * @phpstan-param array<string, string> $tokens
     */
    #[DataProviderExternal(TemplateProvider::class, 'lineEndingNormalizationCases')]
    public function testRenderNormalizesAllLineEndingFormats(
        string $template,
        array $tokens,
        string $expected,
        string $message,
    ): void {
        self::equalsWithoutLE(
            $expected,
            Template::render($template, $tokens),
            $message,
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
