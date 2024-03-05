<?php

declare(strict_types=1);

namespace UIAwesome\Html\Tests\Helper;

use UIAwesome\Html\Helper\Validator;

final class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    public function testInList(): void
    {
        $this->expectNotToPerformAssertions();

        Validator::inList('a', '', 'a', 'b', 'c');
    }

    public function testInListException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value is not in the list: "1".');

        Validator::inList('1', 'The value is not in the list: "%s".', 'a', 'b', 'c');
    }

    public function testInListExceptionWithEmptyValue(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must not be empty. The valid values are: "a", "b", "c".');

        Validator::inList('', '', 'a', 'b', 'c');
    }

    public function testIterable(): void
    {
        $this->expectNotToPerformAssertions();

        Validator::isIterable([]);
    }

    public function testIsIterableException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be an iterable or null value. The value is: string.');

        Validator::isIterable('value');
    }

    public function testIsNumeric(): void
    {
        $this->expectNotToPerformAssertions();

        Validator::isNumeric(1);
    }

    public function testIsNumericException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be a numeric or null value. The value is: string.');

        Validator::isNumeric('value');
    }

    public function testIsScalar(): void
    {
        $this->expectNotToPerformAssertions();

        Validator::isScalar(1);
    }

    public function testIsScalarException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be a scalar or null value. The value is: array.');

        Validator::isScalar([]);
    }

    public function testIsString(): void
    {
        $this->expectNotToPerformAssertions();

        Validator::isString('value');
    }

    public function testIsStringException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The value must be a string or null value. The value is: array.');

        Validator::isString([]);
    }
}
