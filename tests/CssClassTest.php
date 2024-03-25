<?php

declare(strict_types=1);

namespace UIAwesome\Html\Tests\Helper;

use PHPForge\Support\Assert;
use UIAwesome\Html\Helper\CssClass;

final class CssClassTest extends \PHPUnit\Framework\TestCase
{
    public function testAddWithArray(): void
    {
        $attributes = [];

        CssClass::add($attributes, []);
        $this->assertSame([], $attributes);

        CssClass::add($attributes, ['test-class']);
        $this->assertSame(['class' => 'test-class'], $attributes);

        CssClass::add($attributes, ['test-class']);
        $this->assertSame(['class' => 'test-class'], $attributes);

        CssClass::add($attributes, ['test-class-1']);
        $this->assertSame(['class' => 'test-class test-class-1'], $attributes);

        CssClass::add($attributes, ['test-class', 'test-class-1']);
        $this->assertSame(['class' => 'test-class test-class-1'], $attributes);

        CssClass::add($attributes, ['test-class-2']);
        $this->assertSame(['class' => 'test-class test-class-1 test-class-2'], $attributes);

        CssClass::add($attributes, ['test-override-class'], true);
        $this->assertSame(['class' => 'test-override-class'], $attributes);
    }

    public function testAddMethodWithArrayClasses()
    {
        $attributes = ['class' => ['existing-class-1', 'existing-class-2']];

        CssClass::add($attributes, 'new-class');
        $this->assertSame('existing-class-1 existing-class-2 new-class', $attributes['class']);

        $attributes = ['class' => 'existing-class-1 existing-class-2'];

        CssClass::add($attributes, 'new-class');
        $this->assertEquals('existing-class-1 existing-class-2 new-class', $attributes['class']);
    }

    public function testAddWithDefaultValueAttributesIsArray(): void
    {
        $attributes = [];

        CssClass::add($attributes, 'test-class');
        $this->assertSame('test-class', $attributes['class']);

        $attributes = [];

        CssClass::add($attributes, ['test-class-1', 'test-class-2']);
        $this->assertSame('test-class-1 test-class-2', $attributes['class']);
    }

    public function testAddDefaultValueAttributeExistClass(): void
    {
        $attributes = ['class' => 'existing-class'];

        CssClass::add($attributes, 'new-class');
        $this->assertEquals('existing-class new-class', $attributes['class']);
    }

    public function testAddWithString(): void
    {
        $attributes = [];

        CssClass::add($attributes, '');
        $this->assertEmpty($attributes);

        CssClass::add($attributes, 'test-class');
        $this->assertSame(['class' => 'test-class'], $attributes);

        CssClass::add($attributes, 'test-class');
        $this->assertSame(['class' => 'test-class'], $attributes);

        CssClass::add($attributes, 'test-class-1');
        $this->assertSame(['class' => 'test-class test-class-1'], $attributes);

        CssClass::add($attributes, 'test-class test-class-1');
        $this->assertSame(['class' => 'test-class test-class-1'], $attributes);

        CssClass::add($attributes, 'test-class-2');
        $this->assertSame(['class' => 'test-class test-class-1 test-class-2'], $attributes);

        CssClass::add($attributes, 'test-override-class', true);
        $this->assertSame(['class' => 'test-override-class'], $attributes);
    }

    public function testMergeMethodAssignToKey()
    {
        $existingClasses = ['existing-class-1', 'existing-class-2'];
        $additionalClasses = ['keyed-class' => 'new-class'];

        $merged = Assert::invokeMethod(new CssClass(), 'merge', [$existingClasses, $additionalClasses]);

        $this->assertArrayHasKey('keyed-class', $merged);
        $this->assertEquals('new-class', $merged['keyed-class']);
    }

    public function testRender(): void
    {
        $this->assertSame(
            'p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-400',
            CssClass::render(
                'yellow',
                'p-4 mb-4 text-sm text-%1$s-800 rounded-lg bg-%1$s-50 dark:bg-gray-800 dark:text-%1$s-400',
                ['blue', 'gray', 'green', 'red', 'yellow'],
            )
        );
    }

    public function testRenderWithInvalidValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Invalid value: "indigo". Available values: "blue", "gray", "green", "red", "yellow".'
        );

        CssClass::render(
            'indigo',
            'p-4 mb-4 text-sm text-%1$s-800 rounded-lg bg-%1$s-50 dark:bg-gray-800 dark:text-%1$s-400',
            ['blue', 'gray', 'green', 'red', 'yellow'],
        );
    }
}
