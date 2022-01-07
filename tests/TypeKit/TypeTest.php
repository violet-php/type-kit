<?php

declare(strict_types=1);

namespace Violet\TypeKit;

use PHPUnit\Framework\TestCase;
use Violet\TypeKit\Exception\TypeException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeTest extends TestCase
{
    public function testBoolType(): void
    {
        $this->assertTrue(Type::bool(true));
    }

    public function testInvalidBoolType(): void
    {
        $this->expectException(TypeException::class);
        Type::bool(1);
    }

    public function testIntType(): void
    {
        $this->assertSame(1, Type::int(1));
    }

    public function testInvalidIntType(): void
    {
        $this->expectException(TypeException::class);
        Type::int(true);
    }

    public function testFloatType(): void
    {
        $this->assertSame(1.0, Type::float(1.0));
    }

    public function testInvalidFloatType(): void
    {
        $this->expectException(TypeException::class);
        Type::float(true);
    }

    public function testStringType(): void
    {
        $this->assertSame('foobar', Type::string('foobar'));
    }

    public function testInvalidStringType(): void
    {
        $this->expectException(TypeException::class);
        Type::string(true);
    }

    public function testArrayType(): void
    {
        $this->assertSame([], Type::array([]));
        $this->assertSame([0, 1, 2], Type::array([0, 1, 2]));
        $this->assertSame(['foo' => 0], Type::array(['foo' => 0]));
    }

    public function testInvalidArrayType(): void
    {
        $this->expectException(TypeException::class);
        Type::array(true);
    }

    public function testStringArrayType(): void
    {
        $this->assertSame([], Type::stringArray([]));
        $this->assertSame(['a', 'b', 'c'], Type::stringArray(['a', 'b', 'c']));
        $this->assertSame(['foo' => 'bar'], Type::stringArray(['foo' => 'bar']));
    }

    public function testInvalidStringArrayType(): void
    {
        $this->expectException(TypeException::class);
        Type::stringArray(true);
    }

    public function testInvalidStringArrayValueType(): void
    {
        $this->expectException(TypeException::class);
        Type::stringArray([true]);
    }

    public function testListType(): void
    {
        $this->assertSame([], Type::list([]));
        $this->assertSame([0, 1, 2], Type::list([0, 1, 2]));
    }

    public function testInvalidListType(): void
    {
        $this->expectException(TypeException::class);
        Type::list(['foo' => 0]);
    }

    public function testStringListType(): void
    {
        $this->assertSame([], Type::stringList([]));
        $this->assertSame(['a', 'b', 'c'], Type::stringList(['a', 'b', 'c']));
    }

    public function testInvalidStringListType(): void
    {
        $this->expectException(TypeException::class);
        Type::stringList(['foo' => 0]);
    }

    public function testInvalidStringListValueType(): void
    {
        $this->expectException(TypeException::class);
        Type::stringList([0]);
    }

    public function testObjectType(): void
    {
        $object = new \DateTimeImmutable();
        $this->assertSame($object, Type::object($object));
    }

    public function testInvalidObjectType(): void
    {
        $this->expectException(TypeException::class);
        Type::object(true);
    }
}
