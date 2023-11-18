<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use PHPUnit\Framework\Attributes\DataProvider;
use Violet\TypeKit\TypedTestCase;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class ConditionalListTypesTest extends TypedTestCase
{
    #[DataProvider('getValidValuesTestCases')]
    public function testValidValues(\Closure $callback, mixed $value): void
    {
        $this->assertTrue($callback([$value]));
    }

    #[DataProvider('getInvalidValuesTestCases')]
    public function testInvalidValues(\Closure $callback, mixed $value): void
    {
        $this->assertFalse($callback($value));
    }

    #[DataProvider('getInvalidValuesTestCases')]
    public function testInvalidItemValues(\Closure $callback, mixed $value): void
    {
        $this->assertFalse($callback([$value]));
    }

    #[DataProvider('getValidValuesTestCases')]
    public function testValidNonListValues(\Closure $callback, mixed $value): void
    {
        $this->assertFalse($callback([1 => $value]));
    }

    protected static function formatCallback(string $name): array
    {
        return [TypeIs::class, sprintf('%sList', $name)];
    }
}
