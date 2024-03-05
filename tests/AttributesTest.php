<?php

declare(strict_types=1);

namespace UIAwesome\Html\Tests\Helper;

use UIAwesome\Html\Helper\Attributes;

final class AttributesTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider UIAwesome\Html\Helper\Tests\Provider\AttributesProvider::dataRenderTagAttributes
     */
    public function testRenderTagAttributes(string $expected, array $attributes): void
    {
        $this->assertSame($expected, Attributes::render($attributes));
    }
}
