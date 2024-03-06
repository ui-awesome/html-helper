<?php

declare(strict_types=1);

namespace UIAwesome\Html\Tests\Helper;

use UIAwesome\Html\Helper\HTMLBuilder;

final class HTMLBuilderExceptionTest extends \PHPUnit\Framework\TestCase
{
    public function testBeginInlineElement(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Inline elements cannot be used with begin/end syntax.');

        HTMLBuilder::beginTag('br');
    }

    public function testEndInlineElement(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Inline elements cannot be used with begin/end syntax.');

        HTMLBuilder::endTag('br');
    }

    public function testTagEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Tag name cannot be empty.');

        HTMLBuilder::createTag('');
    }
}
