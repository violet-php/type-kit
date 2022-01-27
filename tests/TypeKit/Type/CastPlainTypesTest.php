<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\PhpUnit\AbstractCompliantClass;
use Violet\TypeKit\PhpUnit\CompliantInterface;
use Violet\TypeKit\PhpUnit\NonCompliantClass;
use Violet\TypeKit\TypeCast;
use Violet\TypeKit\Exception\TypeCastException;
use Violet\TypeKit\Exception\InvalidClassException;
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
    public function testNullTypes(): void
    {
        $nulls = [
            TypeCast::null(null),
            TypeCast::null(true),
            TypeCast::null(false),
            TypeCast::null(0),
            TypeCast::null(1),
            TypeCast::null(0.0),
            TypeCast::null(1.0),
            TypeCast::null(''),
            TypeCast::null('1'),
            TypeCast::null([]),
            TypeCast::null(new \stdClass()),
        ];

        $this->assertCount(\count($nulls), array_keys($nulls, null, true));
    }

    public function testBoolTypes(): void
    {
        $this->assertFalse(TypeCast::bool(null));
        $this->assertFalse(TypeCast::bool(false));
        $this->assertFalse(TypeCast::bool(0));
        $this->assertFalse(TypeCast::bool(0.0));
        $this->assertFalse(TypeCast::bool(''));
        $this->assertFalse(TypeCast::bool([]));

        $this->assertTrue(TypeCast::bool(true));
        $this->assertTrue(TypeCast::bool(1));
        $this->assertTrue(TypeCast::bool(0.1));
        $this->assertTrue(TypeCast::bool('0'));
        $this->assertTrue(TypeCast::bool([0]));
        $this->assertTrue(TypeCast::bool(new \stdClass()));
    }

    public function testIntTypes(): void
    {
        $this->assertSame(0, TypeCast::int(null));
        $this->assertSame(0, TypeCast::int(false));
        $this->assertSame(1, TypeCast::int(true));
        $this->assertSame(2, TypeCast::int(2.0));
        $this->assertSame(2, TypeCast::int(2));
        $this->assertSame(3, TypeCast::int('3'));
        $this->assertSame(3, TypeCast::int('+3'));
        $this->assertSame(-3, TypeCast::int('-3'));
        $this->assertSame(3, TypeCast::int('3.0e0'));
    }

    public function testPrecisionLostFloat(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Casting caused a loss of precision');
        TypeCast::int(1.1);
    }

    public function testPrecisionLostStringFloat(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Casting caused a loss of precision');
        TypeCast::int('1.1');
    }

    public function testNonNumericIntString(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Not a numeric string');
        TypeCast::int('foobar');
    }

    public function testInvalidIntType(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Type cannot be cast to int');
        TypeCast::int([]);
    }

    public function testFloatTypes(): void
    {
        $this->assertSame(0.0, TypeCast::float(null));
        $this->assertSame(0.0, TypeCast::float(false));
        $this->assertSame(1.0, TypeCast::float(true));
        $this->assertSame(2.0, TypeCast::float(2));
        $this->assertSame(2.0, TypeCast::float(2.0));
        $this->assertSame(3.0, TypeCast::float('3'));
        $this->assertSame(3.0, TypeCast::float('+3.0'));
        $this->assertSame(-3.0, TypeCast::float('-3.0'));
        $this->assertSame(3.0, TypeCast::float('3.0e0'));
    }

    public function testPrecisionLostInt(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Casting caused a loss of precision');
        TypeCast::float(9223372036854774807);
    }

    public function testPrecisionLostStringInt(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Casting caused a loss of precision');
        TypeCast::float('9223372036854774807');
    }

    public function testNonNumericStringFloat(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Not a numeric string');
        TypeCast::float('foobar');
    }

    public function testInvalidFloatType(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Type cannot be cast to float');
        TypeCast::float([]);
    }

    public function testStringTypes(): void
    {
        $this->assertSame('', TypeCast::string(null));
        $this->assertSame('1', TypeCast::string(true));
        $this->assertSame('', TypeCast::string(false));
        $this->assertSame('123', TypeCast::string(123));
        $this->assertSame('123.123', TypeCast::string(123.123));
        $this->assertSame('foobar', TypeCast::string('foobar'));
        $this->assertMatchesRegularExpression('/test error/', TypeCast::string(new \Exception('test error')));
    }

    public function testObjectWithoutToString(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Cannot cast an object without __toString() method to string');
        TypeCast::string(new \stdClass());
    }

    public function testInvalidStringType(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Type cannot be cast to string');
        TypeCast::string([]);
    }

    public function testArrayTypes(): void
    {
        $this->assertSame(['foo' => 'bar'], TypeCast::array(['foo' => 'bar']));
        $this->assertSame(['foo' => 'bar'], TypeCast::array(new \ArrayObject(['foo' => 'bar'])));
        $this->assertSame(['foo' => 'bar'], TypeCast::array((object)['foo' => 'bar']));
    }

    public function testInvalidArrayType(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Type cannot be cast to array');
        TypeCast::array('');
    }

    public function testFailureOnIteration(): void
    {
        $object = new class () implements \IteratorAggregate {
            public function getIterator(): never
            {
                throw new \RuntimeException('test error');
            }
        };

        $this->expectException(TypeCastException::class);
        TypeCast::array($object);
    }

    public function testListTypes(): void
    {
        $this->assertSame(['bar'], TypeCast::list(['foo' => 'bar']));
        $this->assertSame(['bar'], TypeCast::list(new \ArrayObject(['foo' => 'bar'])));
        $this->assertSame(['bar'], TypeCast::list((object)['foo' => 'bar']));
    }

    public function testInvalidListType(): void
    {
        $this->expectException(TypeCastException::class);
        TypeCast::list('');
    }

    public function testObjectTypes(): void
    {
        $object = new \stdClass();
        $this->assertSame($object, TypeCast::object($object));

        $casted = TypeCast::object(['foo' => 'bar']);
        $this->assertInstanceOf(\stdClass::class, $casted);
        $this->assertObjectHasAttribute('foo', $casted);
        $this->assertSame('bar', $casted->foo);
    }

    public function testInvalidObjectType(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Type cannot be cast to object');
        TypeCast::object('');
    }

    public function testInstanceTypes(): void
    {
        $object = new CompliantClass();
        $this->assertSame($object, TypeCast::instance($object, CompliantClass::class));
        $this->assertSame($object, TypeCast::instance($object, CompliantInterface::class));
        $this->assertSame($object, TypeCast::instance($object, AbstractCompliantClass::class));
    }

    public function testInstanceDoesNotAcceptTrait(): void
    {
        $this->expectException(InvalidClassException::class);
        TypeCast::instance(new CompliantClass(), CompliantTrait::class);
    }

    public function testInvalidObjectValue(): void
    {
        $this->expectException(TypeCastException::class);
        TypeCast::instance(new CompliantClass(), NonCompliantClass::class);
    }

    public function testIterableTypes(): void
    {
        $this->assertSame(['foo' => 'bar'], TypeCast::iterable(['foo' => 'bar']));

        $object = new \ArrayObject(['foo' => 'bar']);
        $this->assertSame($object, TypeCast::iterable($object));
    }

    public function testInvalidIterableValue(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Type cannot be cast to iterable');
        TypeCast::iterable(new \stdClass());
    }

    public function testResourceTypes(): void
    {
        $resource = tmpfile();
        $this->assertSame($resource, TypeCast::resource($resource));
    }

    public function testInvalidResourceValue(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Type cannot be cast to resource');
        TypeCast::resource('');
    }

    public function testCallableTypes(): void
    {
        $this->assertSame('strlen', TypeCast::callable('strlen'));
    }

    public function testInvalidCallableValue(): void
    {
        $this->expectException(TypeCastException::class);
        $this->expectErrorMessage('Type cannot be cast to callable');
        TypeCast::callable('');
    }

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
        } catch (TypeCastException $exception) {
            $pattern = sprintf(
                "/Error trying to cast '[^']+' to '%s'/",
                preg_quote($expectedType, '/')
            );

            $this->assertMatchesRegularExpression($pattern, $exception->getMessage());
            $this->assertSame(0, $exception->getCode());
        }
    }

    protected function formatCallback(string $name): array
    {
        return [TypeCast::class, $name];
    }
}
