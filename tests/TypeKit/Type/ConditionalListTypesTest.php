<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\TypedTestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class ConditionalListTypesTest extends TypedTestCase
{
    /** @dataProvider getValidValuesTestCases */
    public function testValidValues(\Closure $callback, mixed $value): void
    {
        $this->assertTrue($callback([$value]));
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

    /** @dataProvider getValidValuesTestCases */
    public function testValidNonListValues(\Closure $callback, mixed $value): void
    {
        $this->assertFalse($callback([1 => $value]));
    }

    protected function formatCallback(string $name): array
    {
        return [TypeIs::class, sprintf('%sList', $name)];
    }
}
