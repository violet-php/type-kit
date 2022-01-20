<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\TypeAssert;
use Violet\TypeKit\Exception\TypeAssertException;
use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\PhpUnit\CompliantClass;
use Violet\TypeKit\PhpUnit\CompliantTrait;
use Violet\TypeKit\TypedTestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class AssertListTypesTest extends TypedTestCase
{
    /** @dataProvider getValidValuesTestCases */
    public function testValidValues(\Closure $callback, mixed $value): void
    {
        $this->assertNull($callback([$value]));
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidValues(\Closure $callback, mixed $value, string $expectedType): void
    {
        $pattern = sprintf(
            "/Got unexpected value type '[^']+', was expecting 'list<%s>'/",
            preg_quote($expectedType, '/')
        );

        $this->expectException(TypeAssertException::class);
        $this->expectExceptionMessageMatches($pattern);

        $callback($value);
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidItemValues(\Closure $callback, mixed $value): void
    {
        $this->expectException(TypeAssertException::class);
        $callback([$value]);
    }

    /** @dataProvider getValidValuesTestCases */
    public function testValidNonListValues(\Closure $callback, mixed $value): void
    {
        $this->expectException(TypeAssertException::class);
        $callback([1 => $value]);
    }

    public function testInstanceDoesNotAcceptTrait(): void
    {
        $this->expectException(InvalidClassException::class);
        TypeAssert::instanceList([new CompliantClass()], CompliantTrait::class);
    }

    protected function formatCallback(string $name): \Closure
    {
        $name = sprintf('%sList', $name);
        return TypeAssert::$name(...);
    }
}
