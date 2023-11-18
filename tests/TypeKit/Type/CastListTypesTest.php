<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use PHPUnit\Framework\Attributes\DataProvider;
use Violet\TypeKit\Exception\TypeCastException;
use Violet\TypeKit\TypedTestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CastListTypesTest extends TypedTestCase
{
    #[DataProvider('getValidValuesTestCases')]
    public function testValidValues(\Closure $callback, mixed $value): void
    {
        $this->assertSame([$value], $callback([$value]));
    }

    #[DataProvider('getInvalidValuesTestCases')]
    public function testInvalidValues(\Closure $callback, mixed $value, string $expectedType): void
    {
        try {
            $this->assertNotSame($value, $callback($value));
        } catch (TypeCastException $exception) {
            $pattern = sprintf(
                "/Error trying to cast '[^']+' to 'list<%s>'/",
                preg_quote($expectedType, '/')
            );

            $this->assertMatchesRegularExpression($pattern, $exception->getMessage());
            $this->assertSame(0, $exception->getCode());
        }
    }

    #[DataProvider('getInvalidValuesTestCases')]
    public function testInvalidItemValues(\Closure $callback, mixed $value, string $expectedType): void
    {
        try {
            $this->assertNotSame([$value], $callback([$value]));
        } catch (TypeCastException $exception) {
            $pattern = sprintf(
                "/Error trying to cast '[^']+' to 'list<%s>'/",
                preg_quote($expectedType, '/')
            );

            $this->assertMatchesRegularExpression($pattern, $exception->getMessage());
            $this->assertSame(0, $exception->getCode());
        }
    }

    #[DataProvider('getValidValuesTestCases')]
    public function testValidNonListValues(\Closure $callback, mixed $value): void
    {
        $this->assertSame([$value], $callback([1 => $value]));
    }

    public function testWholeArrayIsReturned(): void
    {
        $this->assertSame([1, 2, 3], TypeCast::intList([0 => 1, 'foo' => 2, 3 => '3']));
    }

    protected static function formatCallback(string $name): array
    {
        return [TypeCast::class, sprintf('%sList', $name)];
    }
}
