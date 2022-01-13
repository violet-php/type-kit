<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Cast;
use Violet\TypeKit\Exception\CastException;
use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\Exception\TypeException;
use Violet\TypeKit\PhpUnit\CompliantClass;
use Violet\TypeKit\PhpUnit\CompliantTrait;
use Violet\TypeKit\Type;
use Violet\TypeKit\TypedTestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class CastArrayTypesTest extends TypedTestCase
{
    /** @dataProvider getValidValuesTestCases */
    public function testValidValues(array $call, mixed $value): void
    {
        $callback = $this->getCallback($call);
        $this->assertSame([$value], $callback([$value]));
    }

    /** @dataProvider getValidValuesTestCases */
    public function testValidValuesMap(array $call, mixed $value): void
    {
        $callback = $this->getCallback($call);
        $this->assertSame(['foobar' => $value], $callback(['foobar' => $value]));
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidValues(array $call, mixed $value, string $expectedType): void
    {
        try {
            $callback = $this->getCallback($call);
            $this->assertNotSame($value, $callback($value));
        } catch (CastException $exception) {
            $pattern = sprintf(
                "/Error trying to cast '[^']+' to 'array<%s>'/",
                preg_quote($expectedType, '/')
            );

            $this->assertMatchesRegularExpression($pattern, $exception->getMessage());
        }
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidItemValues(array $call, mixed $value, string $expectedType): void
    {
        try {
            $callback = $this->getCallback($call);
            $this->assertNotSame([$value], $callback([$value]));
        } catch (CastException $exception) {
            $pattern = sprintf(
                "/Error trying to cast '[^']+' to 'array<%s>'/",
                preg_quote($expectedType, '/')
            );

            $this->assertMatchesRegularExpression($pattern, $exception->getMessage());
        }
    }

    public function testInstanceDoesNotAcceptTrait(): void
    {
        $this->expectException(InvalidClassException::class);
        Cast::instanceArray([new CompliantClass()], CompliantTrait::class);
    }

    protected function formatCallback(string $name): \Closure
    {
        $name = sprintf('%sArray', $name);
        return Cast::$name(...);
    }
}
