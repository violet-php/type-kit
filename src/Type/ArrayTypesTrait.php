<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\Exception\TypeException;
use Violet\TypeKit\Type;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait ArrayTypesTrait
{
    /**
     * @param mixed $value
     * @return array<null>
     * @throws TypeException
     */
    public static function nullArray(mixed $value): array
    {
        return Type::isNullArray($value) ? $value : throw TypeException::createFromValue($value, 'array<null>');
    }

    /**
     * @param mixed $value
     * @return array<bool>
     * @throws TypeException
     */
    public static function boolArray(mixed $value): array
    {
        return Type::isBoolArray($value) ? $value : throw TypeException::createFromValue($value, 'array<bool>');
    }

    /**
     * @param mixed $value
     * @return array<int>
     * @throws TypeException
     */
    public static function intArray(mixed $value): array
    {
        return Type::isIntArray($value) ? $value : throw TypeException::createFromValue($value, 'array<int>');
    }

    /**
     * @param mixed $value
     * @return array<float>
     * @throws TypeException
     */
    public static function floatArray(mixed $value): array
    {
        return Type::isFloatArray($value) ? $value : throw TypeException::createFromValue($value, 'array<float>');
    }

    /**
     * @param mixed $value
     * @return array<string>
     * @throws TypeException
     */
    public static function stringArray(mixed $value): array
    {
        return Type::isStringArray($value) ? $value : throw TypeException::createFromValue($value, 'array<string>');
    }

    /**
     * @param mixed $value
     * @return array<array<mixed>>
     * @throws TypeException
     */
    public static function arrayArray(mixed $value): array
    {
        return Type::isArrayArray($value) ? $value : throw TypeException::createFromValue($value, 'array<array>');
    }

    /**
     * @param mixed $value
     * @return array<list<mixed>>
     * @throws TypeException
     */
    public static function listArray(mixed $value): array
    {
        return Type::isListArray($value) ? $value : throw TypeException::createFromValue($value, 'array<list>');
    }

    /**
     * @param mixed $value
     * @return array<object>
     * @throws TypeException
     */
    public static function objectArray(mixed $value): array
    {
        return Type::isObjectArray($value) ? $value : throw TypeException::createFromValue($value, 'array<object>');
    }

    /**
     * @template T
     * @param mixed $value
     * @param class-string<T> $class
     * @return array<T>
     * @throws TypeException
     */
    public static function instanceArray(mixed $value, string $class): array
    {
        return Type::isInstanceArray($value, $class)
            ? $value
            : throw TypeException::createFromValue($value, "array<$class>");
    }

    /**
     * @param mixed $value
     * @return array<iterable<mixed>>
     * @throws TypeException
     */
    public static function iterableArray(mixed $value): array
    {
        return Type::isIterableArray($value) ? $value : throw TypeException::createFromValue($value, 'array<iterable>');
    }

    /**
     * @param mixed $value
     * @return array<resource>
     * @throws TypeException
     */
    public static function resourceArray(mixed $value): array
    {
        return Type::isResourceArray($value) ? $value : throw TypeException::createFromValue($value, 'array<resource>');
    }

    /**
     * @param mixed $value
     * @return array<callable>
     * @throws TypeException
     */
    public static function callableArray(mixed $value): array
    {
        return Type::isCallableArray($value) ? $value : throw TypeException::createFromValue($value, 'array<callable>');
    }
}
