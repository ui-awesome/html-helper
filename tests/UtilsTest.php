<?php

declare(strict_types=1);

namespace UIAwesome\Html\Tests\Helper;

use UIAwesome\Html\Helper\Utils;

final class UtilsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider UIAwesome\Html\Helper\Tests\Provider\UtilsProvider::convertToPattern
     *
     * @param string $expected The expected result.
     * @param string $regexp The regexp pattern to normalize.
     * @param string|null $delimiter The delimiter to use.
     */
    public function testConvertToPattern(string $expected, string $regexp, string $delimiter = null): void
    {
        $this->assertSame($expected, Utils::convertToPattern($regexp, $delimiter));
    }

    /**
     * @dataProvider UIAwesome\Html\Helper\Tests\Provider\UtilsProvider::convertToPatternInvalid
     *
     * @param string $regexp The regexp pattern to normalize.
     * @param string $message The expected exception message.
     * @param string|null $delimiter The delimiter to use.
     */
    public function testConvertToPatterInvalid(string $regexp, string $message, ?string $delimiter = null): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($message);

        Utils::convertToPattern($regexp, $delimiter);
    }

    public function testGenerateArrayableName(): void
    {
        $this->assertSame('test.name[]', Utils::generateArrayableName('test.name'));
    }

    public function testGenerateId(): void
    {
        $this->assertMatchesRegularExpression('/^id-[0-9a-f]{13}$/', Utils::generateId());
    }

    public function testGenerateIdWithPrefix(): void
    {
        $this->assertMatchesRegularExpression('/^prefix-[0-9a-f]{13}$/', Utils::generateId('prefix-'));
    }

    public function testGenerateInputId(): void
    {
        $this->assertSame('utilstest-string', Utils::generateInputId('UtilsTest', 'string'));
    }

    public function testGenerateInputName(): void
    {
        $this->assertSame('TestForm[content][body]', Utils::generateInputName('TestForm', 'content[body]'));
    }

    /**
     * @dataProvider UIAwesome\Html\Helper\Tests\Provider\UtilsProvider::dataGetInputName
     */
    public function testGetInputNameDataProvider(string $formName, string $attribute, bool $arrayable, string $expected): void
    {
        $this->assertSame($expected, Utils::generateInputName($formName, $attribute, $arrayable));
    }

    public function testGetInputNameWithArrayableTrue(): void
    {
        $this->assertSame('TestForm[content][body][]', Utils::generateInputName('TestForm', 'content[body]', true));
    }

    public function testGetInputNamewithOnlyCharacters(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Property name must contain word characters only.');

        Utils::generateInputName('TestForm', 'content body');
    }

    public function testGetInputNameExceptionWithTabular(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The form model name cannot be empty for tabular inputs.');

        Utils::generateInputName('', '[0]dates[0]');
    }

    public function testGetShortNameClass(): void
    {
        $this->assertSame('UtilsTest::class', Utils::getShortNameClass(self::class));
    }

    public function testGetShortNameClassWithLowercase(): void
    {
        $this->assertSame('utilstest', Utils::getShortNameClass(self::class, false, true));
    }

    public function testGetShortNameClassWithoutSuffix(): void
    {
        $this->assertSame('UtilsTest', Utils::getShortNameClass(self::class, false));
    }

    public function testMultibyteGenerateArrayableName(): void
    {
        $this->assertSame('登录[]', Utils::generateArrayableName('登录'));
        $this->assertSame('登录[]', Utils::generateArrayableName('登录[]'));
        $this->assertSame('登录[0][]', Utils::generateArrayableName('登录[0]'));
        $this->assertSame('[0]登录[0][]', Utils::generateArrayableName('[0]登录[0]'));
    }

    public function testMultibyteGenerateInputId(): void
    {
        $this->assertSame('testform-mąka', Utils::generateInputId('TestForm', 'mĄkA'));
    }
}
