<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\PhpUnit\CompliantClass;
use Violet\TypeKit\PhpUnit\CompliantTrait;
use Violet\TypeKit\TypedTestCase;
use Violet\TypeKit\TypeIs;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class ConditionalArrayTypesTest extends TypedTestCase
{
    /** @dataProvider getValidValuesTestCases */
    public function testValidValues(\Closure $callback, mixed $value): void
    {
        $this->assertTrue($callback([$value]));
    }

    /** @dataProvider getValidValuesTestCases */
    public function testValidValuesMap(\Closure $callback, mixed $value): void
    {
        $this->assertTrue($callback(['foobar' => $value]));
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidValues(\Closure $callback, mixed $value): void
    {
        $this->assertFalse($callback($value));
    }

    /** @dataProvider getInvalidValuesTestCases */
    public function testInvalidItemValues(\Closure $callback, mixed $value): void
    {
        $this->assertFalse($callback([$value]));
    }

    public function testInstanceDoesNotAcceptTrait(): void
    {
        $this->expectException(InvalidClassException::class);
        TypeIs::instanceArray([new CompliantClass()], CompliantTrait::class);
    }

    protected function formatCallback(string $name): array
    {
        return [TypeIs::class, sprintf('%sArray', $name)];
    }
}
