<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\Exception\TypeAssertException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeAssert
{
    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert null $value
     */
    public static function null(mixed $value): void
    {
        if ($value !== null) {
            throw TypeAssertException::createFromValue($value, 'null');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert bool $value
     */
    public static function bool(mixed $value): void
    {
        if (!\is_bool($value)) {
            throw TypeAssertException::createFromValue($value, 'bool');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert int $value
     */
    public static function int(mixed $value): void
    {
        if (!\is_int($value)) {
            throw TypeAssertException::createFromValue($value, 'int');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert float $value
     */
    public static function float(mixed $value): void
    {
        if (!\is_float($value)) {
            throw TypeAssertException::createFromValue($value, 'float');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert string $value
     */
    public static function string(mixed $value): void
    {
        if (!\is_string($value)) {
            throw TypeAssertException::createFromValue($value, 'string');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<mixed> $value
     */
    public static function array(mixed $value): void
    {
        if (!\is_array($value)) {
            throw TypeAssertException::createFromValue($value, 'array');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<mixed> $value
     */
    public static function list(mixed $value): void
    {
        if (!\is_array($value) || !array_is_list($value)) {
            throw TypeAssertException::createFromValue($value, 'list');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert object $value
     */
    public static function object(mixed $value): void
    {
        if (!\is_object($value)) {
            throw TypeAssertException::createFromValue($value, 'object');
        }
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @throws TypeAssertException
     * @psalm-assert T $value
     */
    public static function instance(mixed $value, string $class): void
    {
        if ($value instanceof $class) {
            return;
        }

        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        throw TypeAssertException::createFromValue($value, $class);
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert iterable<mixed> $value
     */
    public static function iterable(mixed $value): void
    {
        if (!\is_iterable($value)) {
            throw TypeAssertException::createFromValue($value, 'iterable');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert resource $value
     */
    public static function resource(mixed $value): void
    {
        if (!\is_resource($value)) {
            throw TypeAssertException::createFromValue($value, 'resource');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert callable $value
     */
    public static function callable(mixed $value): void
    {
        if (!\is_callable($value)) {
            throw TypeAssertException::createFromValue($value, 'callable');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<null> $value
     */
    public static function nullArray(mixed $value): void
    {
        if (!TypeIs::nullArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<null>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<bool> $value
     */
    public static function boolArray(mixed $value): void
    {
        if (!TypeIs::boolArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<bool>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<int> $value
     */
    public static function intArray(mixed $value): void
    {
        if (!TypeIs::intArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<int>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<float> $value
     */
    public static function floatArray(mixed $value): void
    {
        if (!TypeIs::floatArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<float>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<string> $value
     */
    public static function stringArray(mixed $value): void
    {
        if (!TypeIs::stringArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<string>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<array<mixed>> $value
     */
    public static function arrayArray(mixed $value): void
    {
        if (!TypeIs::arrayArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<array>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<list<mixed>> $value
     */
    public static function listArray(mixed $value): void
    {
        if (!TypeIs::listArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<list>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<object> $value
     */
    public static function objectArray(mixed $value): void
    {
        if (!TypeIs::objectArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<object>');
        }
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @throws TypeAssertException
     * @psalm-assert array<T> $value
     */
    public static function instanceArray(mixed $value, string $class): void
    {
        if (!TypeIs::instanceArray($value, $class)) {
            throw TypeAssertException::createFromValue($value, "array<$class>");
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<iterable<mixed>> $value
     */
    public static function iterableArray(mixed $value): void
    {
        if (!TypeIs::iterableArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<iterable>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<resource> $value
     */
    public static function resourceArray(mixed $value): void
    {
        if (!TypeIs::resourceArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<resource>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<callable> $value
     */
    public static function callableArray(mixed $value): void
    {
        if (!TypeIs::callableArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<callable>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<null> $value
     */
    public static function nullList(mixed $value): void
    {
        if (!TypeIs::nullList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<null>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<bool> $value
     */
    public static function boolList(mixed $value): void
    {
        if (!TypeIs::boolList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<bool>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<int> $value
     */
    public static function intList(mixed $value): void
    {
        if (!TypeIs::intList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<int>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<float> $value
     */
    public static function floatList(mixed $value): void
    {
        if (!TypeIs::floatList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<float>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<string> $value
     */
    public static function stringList(mixed $value): void
    {
        if (!TypeIs::stringList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<string>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<array<mixed>> $value
     */
    public static function arrayList(mixed $value): void
    {
        if (!TypeIs::arrayList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<array>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<list<mixed>> $value
     */
    public static function listList(mixed $value): void
    {
        if (!TypeIs::listList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<list>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<object> $value
     */
    public static function objectList(mixed $value): void
    {
        if (!TypeIs::objectList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<object>');
        }
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @throws TypeAssertException
     * @psalm-assert list<T> $value
     */
    public static function instanceList(mixed $value, string $class): void
    {
        if (!TypeIs::instanceList($value, $class)) {
            throw TypeAssertException::createFromValue($value, "list<$class>");
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<iterable<mixed>> $value
     */
    public static function iterableList(mixed $value): void
    {
        if (!TypeIs::iterableList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<iterable>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<resource> $value
     */
    public static function resourceList(mixed $value): void
    {
        if (!TypeIs::resourceList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<resource>');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<callable> $value
     */
    public static function callableList(mixed $value): void
    {
        if (!TypeIs::callableList($value)) {
            throw TypeAssertException::createFromValue($value, 'list<callable>');
        }
    }
}
