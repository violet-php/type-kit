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
class ConditionalArrayTypesTest extends TypedTestCase
{
    #[DataProvider('getValidValuesTestCases')]
    public function testValidValues(\Closure $callback, mixed $value): void
    {
        $this->assertTrue($callback([$value]));
    }

    #[DataProvider('getValidValuesTestCases')]
    public function testValidValuesMap(\Closure $callback, mixed $value): void
    {
        $this->assertTrue($callback(['foobar' => $value]));
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

    protected static function formatCallback(string $name): array
    {
        return [TypeIs::class, sprintf('%sArray', $name)];
    }
}
