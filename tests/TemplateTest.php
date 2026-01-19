<?php

declare(strict_types=1);

namespace UIAwesome\Html\Helper\Tests;

use PHPForge\Support\LineEndingNormalizer;
use PHPUnit\Framework\Attributes\{DataProviderExternal, Group};
use PHPUnit\Framework\TestCase;
use UIAwesome\Html\Helper\Template;
use UIAwesome\Html\Helper\Tests\Support\Provider\TemplateProvider;

/**
 * Unit tests for {@see Template} template rendering behavior.
 *
 * Verifies observable behavior for {@see Template} based on this test file only (test methods, providers, and
 * assertions). Statements must be grounded in datasets, assertions, and explicit exception expectations present here.
 *
 * Test coverage.
 * - Line ending normalization for CRLF, CR, and LF inputs.
 * - Rendering with actual newlines and token replacement.
 * - Rendering with empty or missing tokens.
 *
 * {@see Template} for implementation details.
 * {@see TemplateProvider} for test case data providers.
 *
 * @copyright Copyright (C) 2025 Terabytesoftw.
 * @license https://opensource.org/license/bsd-3-clause BSD 3-Clause License.
 */
#[Group('helper')]
final class TemplateTest extends TestCase
{
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
        self::assertEquals(
            LineEndingNormalizer::normalize(
                $expected,
            ),
            LineEndingNormalizer::normalize(
                Template::render($template, $tokens),
            ),
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

        self::assertEquals(
            LineEndingNormalizer::normalize(
            $expected,
            ),
            LineEndingNormalizer::normalize(
                $result,
            ),
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

        self::assertEquals(
            <<<HTML
            prefix
            <div>content</div>
            HTML,
            LineEndingNormalizer::normalize(
                Template::render($template, $tokenValues),
            ),
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

        self::assertEquals(
            <<<HTML
            prefix
            <div>content</div>
            HTML,
            LineEndingNormalizer::normalize(
                Template::render($template, $tokenValues),
            ),
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

        self::assertEquals(
            <<<HTML
            <div>content</div>
            suffix
            HTML,
            LineEndingNormalizer::normalize(
                Template::render($template, $tokenValues),
            ),
            'Should render template with empty or missing tokens.',
        );
    }
}
