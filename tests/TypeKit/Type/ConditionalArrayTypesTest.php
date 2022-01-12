<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\PhpUnit\CompliantClass;
use Violet\TypeKit\PhpUnit\CompliantTrait;
use Violet\TypeKit\Type;
use Violet\TypeKit\TypedTestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class ConditionalArrayTypesTest extends TypedTestCase
{
    /** @dataProvider getValidValuesTestCases */
    public function testValidValues(array $call, mixed $value): void
    {
        $callback = $this->getCallback($call);
        $this->assertTrue($callback([$value]));
    }

    /** @dataProvider getValidValuesTestCases */
    public function testValidValuesMap(array $call, mixed $value): void
    {
        $callback = $this->getCallback($call);
        $this->assertTrue($callback(['foobar' => $value]));
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidValues(array $call, mixed $value): void
    {
        $callback = $this->getCallback($call);
        $this->assertFalse($callback($value));
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidItemValues(array $call, mixed $value): void
    {
        $callback = $this->getCallback($call);
        $this->assertFalse($callback([$value]));
    }

    public function testInstanceDoesNotAcceptTrait(): void
    {
        $this->expectException(InvalidClassException::class);
        Type::isInstanceArray([new CompliantClass()], CompliantTrait::class);
    }

    protected function formatCallback(string $name): \Closure
    {
        $name = sprintf('is%sArray', ucfirst($name));
        return Type::$name(...);
    }
}
