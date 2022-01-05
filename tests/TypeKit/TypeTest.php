<?php

declare(strict_types=1);

namespace TypeKit;

use PHPUnit\Framework\TestCase;
use Violet\TypeKit\Exception\TypeException;
use Violet\TypeKit\Type;

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

    public function testStringType(): void
    {
        $this->assertSame('foobar', Type::string('foobar'));
    }

    public function testInvalidStringType(): void
    {
        $this->expectException(TypeException::class);
        Type::string(true);
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
