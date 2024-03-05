<?php

declare(strict_types=1);

namespace UIAwesome\Html\Tests\Helper;

use PHPForge\Support\Assert;
use UIAwesome\Html\Helper\Template;

final class TemplateTest extends \PHPUnit\Framework\TestCase
{
    public function testRender(): void
    {
        $template = '{{prefix}}\n{{tag}}\n{{suffix}}';
        $tokenValues = [
            '{{prefix}}' => 'prefix',
            '{{tag}}' => '<div>content</div>',
            '{{suffix}}' => '',
        ];

        Assert::equalsWithoutLE(
            <<<HTML
            prefix
            <div>content</div>
            HTML,
            Template::render($template, $tokenValues)
        );
    }

    public function testRenderWithEmptyValuesTokens(): void
    {
        $template = '{{prefix}}\n{{tag}}\n{{suffix}}';
        $tokenValues = [
            '{{prefix}}' => '',
            '{{label}}' => '',
            '{{tag}}' => '<div>content</div>',
            '{{suffix}}' => 'suffix',
        ];

        Assert::equalsWithoutLE(
            <<<HTML
            <div>content</div>
            suffix
            HTML,
            Template::render($template, $tokenValues)
        );
    }
}
