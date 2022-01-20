<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\TypeCast;
use Violet\TypeKit\Exception\CastException;
use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\Pcre\Regex;
use Violet\TypeKit\PhpUnit\CompliantClass;
use Violet\TypeKit\PhpUnit\CompliantTrait;
use Violet\TypeKit\TypedTestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CastPlainTypesTest extends TypedTestCase
{
    /** @dataProvider getValidValuesTestCases */
    public function testValidValues(\Closure $callback, mixed $value): void
    {
        $this->assertSame($value, $callback($value));
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidValues(\Closure $callback, mixed $value, string $expectedType): void
    {
        try {
            $this->assertNotSame($value, $callback($value));
        } catch (CastException $exception) {
            $pattern = sprintf(
                "/Error trying to cast '[^']+' to '%s'/",
                preg_quote($expectedType, '/')
            );

            $this->assertMatchesRegularExpression($pattern, $exception->getMessage());
        }
    }

    public function testCastingTraversable(): void
    {
        $this->assertSame([1, 2, 3, 4, 5], TypeCast::array((static fn () => yield from range(1, 5))()));
    }

    public function testCastingObject(): void
    {
        $this->assertSame(['regex' => '//'], TypeCast::array(new Regex('//')));
    }

    public function testInstanceDoesNotAcceptTrait(): void
    {
        $this->expectException(InvalidClassException::class);
        TypeCast::instance(new CompliantClass(), CompliantTrait::class);
    }

    protected function formatCallback(string $name): \Closure
    {
        return TypeCast::$name(...);
    }
}
