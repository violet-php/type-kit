<?php

declare(strict_types=1);

namespace Violet\TypeKit;

use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\Exception\TypeException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeAs
{
    /**
     * @param mixed $value
     * @return null
     * @throws TypeException
     */
    public static function null(mixed $value): mixed
    {
        if ($value === null) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'null');
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws TypeException
     */
    public static function bool(mixed $value): bool
    {
        if (\is_bool($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'bool');
    }

    /**
     * @param mixed $value
     * @return int
     * @throws TypeException
     */
    public static function int(mixed $value): int
    {
        if (\is_int($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'int');
    }

    /**
     * @param mixed $value
     * @return float
     * @throws TypeException
     */
    public static function float(mixed $value): float
    {
        if (\is_float($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'float');
    }

    /**
     * @param mixed $value
     * @return string
     * @throws TypeException
     */
    public static function string(mixed $value): string
    {
        if (\is_string($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'string');
    }

    /**
     * @param mixed $value
     * @return array<mixed>
     * @throws TypeException
     */
    public static function array(mixed $value): array
    {
        if (\is_array($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array');
    }

    /**
     * @param mixed $value
     * @return list<mixed>
     * @throws TypeException
     */
    public static function list(mixed $value): array
    {
        if (\is_array($value) && array_is_list($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list');
    }

    /**
     * @param mixed $value
     * @return object
     * @throws TypeException
     */
    public static function object(mixed $value): object
    {
        if (\is_object($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'object');
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return T
     * @throws TypeException
     */
    public static function instance(mixed $value, string $class): object
    {
        if ($value instanceof $class) {
            return $value;
        }

        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        throw TypeException::createFromValue($value, $class);
    }

    /**
     * @param mixed $value
     * @return iterable<mixed>
     * @throws TypeException
     */
    public static function iterable(mixed $value): iterable
    {
        if (is_iterable($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'iterable');
    }

    /**
     * @param mixed $value
     * @return resource
     * @throws TypeException
     */
    public static function resource(mixed $value): mixed
    {
        if (\is_resource($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'resource');
    }

    /**
     * @param mixed $value
     * @return callable
     * @throws TypeException
     */
    public static function callable(mixed $value): callable
    {
        if (\is_callable($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'callable');
    }

    /**
     * @param mixed $value
     * @return array<null>
     * @throws TypeException
     */
    public static function nullArray(mixed $value): array
    {
        if (TypeIs::nullArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<null>');
    }

    /**
     * @param mixed $value
     * @return array<bool>
     * @throws TypeException
     */
    public static function boolArray(mixed $value): array
    {
        if (TypeIs::boolArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<bool>');
    }

    /**
     * @param mixed $value
     * @return array<int>
     * @throws TypeException
     */
    public static function intArray(mixed $value): array
    {
        if (TypeIs::intArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<int>');
    }

    /**
     * @param mixed $value
     * @return array<float>
     * @throws TypeException
     */
    public static function floatArray(mixed $value): array
    {
        if (TypeIs::floatArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<float>');
    }

    /**
     * @param mixed $value
     * @return array<string>
     * @throws TypeException
     */
    public static function stringArray(mixed $value): array
    {
        if (TypeIs::stringArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<string>');
    }

    /**
     * @param mixed $value
     * @return array<array<mixed>>
     * @throws TypeException
     */
    public static function arrayArray(mixed $value): array
    {
        if (TypeIs::arrayArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<array>');
    }

    /**
     * @param mixed $value
     * @return array<list<mixed>>
     * @throws TypeException
     */
    public static function listArray(mixed $value): array
    {
        if (TypeIs::listArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<list>');
    }

    /**
     * @param mixed $value
     * @return array<object>
     * @throws TypeException
     */
    public static function objectArray(mixed $value): array
    {
        if (TypeIs::objectArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<object>');
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return array<T>
     * @throws TypeException
     */
    public static function instanceArray(mixed $value, string $class): array
    {
        if (TypeIs::instanceArray($value, $class)) {
            return $value;
        }

        throw TypeException::createFromValue($value, "array<$class>");
    }

    /**
     * @param mixed $value
     * @return array<iterable<mixed>>
     * @throws TypeException
     */
    public static function iterableArray(mixed $value): array
    {
        if (TypeIs::iterableArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<iterable>');
    }

    /**
     * @param mixed $value
     * @return array<resource>
     * @throws TypeException
     */
    public static function resourceArray(mixed $value): array
    {
        if (TypeIs::resourceArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<resource>');
    }

    /**
     * @param mixed $value
     * @return array<callable>
     * @throws TypeException
     */
    public static function callableArray(mixed $value): array
    {
        if (TypeIs::callableArray($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'array<callable>');
    }

    /**
     * @param mixed $value
     * @return list<null>
     * @throws TypeException
     */
    public static function nullList(mixed $value): array
    {
        if (TypeIs::nullList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<null>');
    }

    /**
     * @param mixed $value
     * @return list<bool>
     * @throws TypeException
     */
    public static function boolList(mixed $value): array
    {
        if (TypeIs::boolList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<bool>');
    }

    /**
     * @param mixed $value
     * @return list<int>
     * @throws TypeException
     */
    public static function intList(mixed $value): array
    {
        if (TypeIs::intList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<int>');
    }

    /**
     * @param mixed $value
     * @return list<float>
     * @throws TypeException
     */
    public static function floatList(mixed $value): array
    {
        if (TypeIs::floatList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<float>');
    }

    /**
     * @param mixed $value
     * @return list<string>
     * @throws TypeException
     */
    public static function stringList(mixed $value): array
    {
        if (TypeIs::stringList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<string>');
    }

    /**
     * @param mixed $value
     * @return list<array<mixed>>
     * @throws TypeException
     */
    public static function arrayList(mixed $value): array
    {
        if (TypeIs::arrayList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<array>');
    }

    /**
     * @param mixed $value
     * @return list<list<mixed>>
     * @throws TypeException
     */
    public static function listList(mixed $value): array
    {
        if (TypeIs::listList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<list>');
    }

    /**
     * @param mixed $value
     * @return list<object>
     * @throws TypeException
     */
    public static function objectList(mixed $value): array
    {
        if (TypeIs::objectList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<object>');
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return list<T>
     * @throws TypeException
     */
    public static function instanceList(mixed $value, string $class): array
    {
        if (TypeIs::instanceList($value, $class)) {
            return $value;
        }

        throw TypeException::createFromValue($value, "list<$class>");
    }

    /**
     * @param mixed $value
     * @return list<iterable<mixed>>
     * @throws TypeException
     */
    public static function iterableList(mixed $value): array
    {
        if (TypeIs::iterableList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<iterable>');
    }

    /**
     * @param mixed $value
     * @return list<resource>
     * @throws TypeException
     */
    public static function resourceList(mixed $value): array
    {
        if (TypeIs::resourceList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<resource>');
    }

    /**
     * @param mixed $value
     * @return list<callable>
     * @throws TypeException
     */
    public static function callableList(mixed $value): array
    {
        if (TypeIs::callableList($value)) {
            return $value;
        }

        throw TypeException::createFromValue($value, 'list<callable>');
    }
}
