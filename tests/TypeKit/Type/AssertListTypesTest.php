<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Assert;
use Violet\TypeKit\Exception\AssertException;
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
    public function testValidValues(array $call, mixed $value): void
    {
        $callback = $this->getCallback($call);
        $this->assertNull($callback([$value]));
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidValues(array $call, mixed $value, string $expectedType): void
    {
        $pattern = sprintf(
            "/Got unexpected value type '[^']+', was expecting 'list<%s>'/",
            preg_quote($expectedType, '/')
        );

        $callback = $this->getCallback($call);

        $this->expectException(AssertException::class);
        $this->expectExceptionMessageMatches($pattern);

        $callback($value);
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidItemValues(array $call, mixed $value): void
    {
        $callback = $this->getCallback($call);
        $this->expectException(AssertException::class);
        $callback([$value]);
    }

    /** @dataProvider getValidValuesTestCases */
    public function testValidNonListValues(array $call, mixed $value): void
    {
        $callback = $this->getCallback($call);
        $this->expectException(AssertException::class);
        $callback([1 => $value]);
    }

    public function testInstanceDoesNotAcceptTrait(): void
    {
        $this->expectException(InvalidClassException::class);
        Assert::instanceList([new CompliantClass()], CompliantTrait::class);
    }

    protected function formatCallback(string $name): \Closure
    {
        $name = sprintf('%sList', $name);
        return Assert::$name(...);
    }
}
