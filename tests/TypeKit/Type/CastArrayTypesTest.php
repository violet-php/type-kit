<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\TypeCastException;
use Violet\TypeKit\TypedTestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CastArrayTypesTest extends TypedTestCase
{
    /** @dataProvider getValidValuesTestCases */
    public function testValidValues(\Closure $callback, mixed $value): void
    {
        $this->assertSame([$value], $callback([$value]));
    }

    /** @dataProvider getValidValuesTestCases */
    public function testValidValuesMap(\Closure $callback, mixed $value): void
    {
        $this->assertSame(['foobar' => $value], $callback(['foobar' => $value]));
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidValues(\Closure $callback, mixed $value, string $expectedType): void
    {
        try {
            $this->assertNotSame($value, $callback($value));
        } catch (TypeCastException $exception) {
            $pattern = sprintf(
                "/Error trying to cast '[^']+' to 'array<%s>'/",
                preg_quote($expectedType, '/')
            );

            $this->assertMatchesRegularExpression($pattern, $exception->getMessage());
            $this->assertSame(0, $exception->getCode());
        }
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidItemValues(\Closure $callback, mixed $value, string $expectedType): void
    {
        try {
            $this->assertNotSame([$value], $callback([$value]));
        } catch (TypeCastException $exception) {
            $pattern = sprintf(
                "/Error trying to cast '[^']+' to 'array<%s>'/",
                preg_quote($expectedType, '/')
            );

            $this->assertMatchesRegularExpression($pattern, $exception->getMessage());
            $this->assertSame(0, $exception->getCode());
        }
    }

    public function testWholeArrayIsReturned(): void
    {
        $this->assertSame([0 => 1, 'foo' => 2, 3 => 3], TypeCast::intArray([0 => 1, 'foo' => 2, 3 => '3']));
    }

    protected function formatCallback(string $name): array
    {
        return [TypeCast::class, sprintf('%sArray', $name)];
    }
}
