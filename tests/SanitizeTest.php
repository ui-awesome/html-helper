<?php

declare(strict_types=1);

namespace UIAwesome\Html\Tests\Helper;

use PHPForge\Support\Assert;
use UIAwesome\{Html\Helper\Sanitize, Html\Helper\Tests\Support\InputWidget};

final class SanitizeTest extends \PHPUnit\Framework\TestCase
{
    public function testInitializate(): void
    {
        Sanitize::initialize(['form', 'style'], ['button', 'form', 'input', 'select', 'svg', 'textarea']);

        $this->assertSame(
            ['form', 'style'],
            Assert::inaccessibleProperty(new Sanitize(), 'removeEvilAttributes')
        );
        $this->assertSame(
            ['button', 'form', 'input', 'select', 'svg', 'textarea'],
            Assert::inaccessibleProperty(new Sanitize(), 'removeEvilHtmlTags')
        );
    }

    public function testHtml(): void
    {
        $this->assertSame(
            '<a >click</a>',
            Sanitize::html('<a href=&#x2000;javascript:alert(1)>click</a>')
        );
        $this->assertSame(
            '<button><img src="http://fakeurl.com/fake.jpg" /></button>',
            Sanitize::html('<button><img src="http://fakeurl.com/fake.jpg" onerror="alert(\'XSS\')"/></button>')
        );
        $this->assertSame(
            '<form><input type="text" value="test" /></form>',
            Sanitize::html('<form><input type="text" value="test" onfocus="alert(\'XSS\')"/></form>')
        );
        $this->assertSame(
            '<img >',
            Sanitize::html('<img src=&#x6A&#x61&#x76&#x61&#x73&#x63&#x72&#x69&#x70&#x74&#x3A&#x61&#x6C&#x65&#x72&#x74&#x28&#x27&#x58&#x53&#x53&#x27&#x29>')
        );
        $this->assertSame(
            '<input type="text" value="test"  />',
            Sanitize::html('<input type="text" value="test" onfocus="alert(\'XSS\')" />')
        );
        $this->assertSame(
            '<select><option value="test">test</option></select>',
            Sanitize::html('<select><option value="test">test</option></select>')
        );
        $this->assertSame(
            '<svg></svg>',
            Sanitize::html('<svg><script>alert("XSS")</script></svg>')
        );
        $this->assertSame(
            '<textarea></textarea>',
            Sanitize::html('<textarea><script>alert("XSS")</script></textarea>')
        );
        $this->assertSame(
            '<div><input type="text" value="test" style="padding-left:20px" oinvalid=""  /></div>',
            Sanitize::html(
                '<div>',
                '<input type="text" value="test" style="padding-left:20px" oinvalid="" onfocus="alert(\'XSS\')" />',
                '</div>'
            )
        );
    }

    public function testHtmlWithRenderInterface(): void
    {
        $this->assertSame(
            '<input type="text" value="test" style="padding-left:20px" oinvalid=""  />',
            Sanitize::html(new InputWidget())
        );
    }
}
