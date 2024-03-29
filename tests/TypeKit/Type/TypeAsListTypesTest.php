<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use PHPUnit\Framework\Attributes\DataProvider;
use Violet\TypeKit\Exception\TypeException;
use Violet\TypeKit\TypedTestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeAsListTypesTest extends TypedTestCase
{
    #[DataProvider('getValidValuesTestCases')]
    public function testValidValues(\Closure $callback, mixed $value): void
    {
        $this->assertSame([$value, $value], $callback([$value, $value]));
    }

    #[DataProvider('getInvalidValuesTestCases')]
    public function testInvalidValues(\Closure $callback, mixed $value, string $expectedType): void
    {
        $pattern = sprintf(
            "/Got unexpected value type '[^']+', was expecting 'list<%s>'/",
            preg_quote($expectedType, '/')
        );

        $this->expectException(TypeException::class);
        $this->expectExceptionMessageMatches($pattern);
        $this->expectExceptionCode(0);

        $callback($value);
    }

    #[DataProvider('getInvalidValuesTestCases')]
    public function testInvalidItemValues(\Closure $callback, mixed $value): void
    {
        $this->expectException(TypeException::class);
        $callback([$value]);
    }

    #[DataProvider('getValidValuesTestCases')]
    public function testValidNonListValues(\Closure $callback, mixed $value): void
    {
        $this->expectException(TypeException::class);
        $callback([1 => $value]);
    }

    protected static function formatCallback(string $name): array
    {
        return [TypeAs::class, sprintf('%sList', $name)];
    }
}
