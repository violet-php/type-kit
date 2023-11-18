<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use PHPUnit\Framework\Attributes\DataProvider;
use Violet\TypeKit\Exception\TypeAssertException;
use Violet\TypeKit\TypedTestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class AssertArrayTypesTest extends TypedTestCase
{
    #[DataProvider('getValidValuesTestCases')]
    public function testValidValues(\Closure $callback, mixed $value): void
    {
        $this->assertNull($callback([$value]));
    }

    #[DataProvider('getValidValuesTestCases')]
    public function testValidValuesMap(\Closure $callback, mixed $value): void
    {
        $this->assertNull($callback(['foobar' => $value]));
    }

    #[DataProvider('getInvalidValuesTestCases')]
    public function testInvalidValues(\Closure $callback, mixed $value, string $expectedType): void
    {
        $pattern = sprintf(
            "/Got unexpected value type '[^']+', was expecting 'array<%s>'/",
            preg_quote($expectedType, '/')
        );

        $this->expectException(TypeAssertException::class);
        $this->expectExceptionMessageMatches($pattern);
        $this->expectExceptionCode(0);

        $callback($value);
    }

    #[DataProvider('getInvalidValuesTestCases')]
    public function testInvalidItemValues(\Closure $callback, mixed $value): void
    {
        $this->expectException(TypeAssertException::class);
        $callback([$value]);
    }

    protected static function formatCallback(string $name): array
    {
        return [TypeAssert::class, sprintf('%sArray', $name)];
    }
}
