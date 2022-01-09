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
    public static function nullArray(mixed $value): array
    {
        return Type::isNullArray($value) ? $value : throw TypeException::createFromValue($value, 'array<null>');
    }

    public static function boolArray(mixed $value): array
    {
        return Type::isBoolArray($value) ? $value : throw TypeException::createFromValue($value, 'array<bool>');
    }

    public static function intArray(mixed $value): array
    {
        return Type::isIntArray($value) ? $value : throw TypeException::createFromValue($value, 'array<int>');
    }

    public static function floatArray(mixed $value): array
    {
        return Type::isFloatArray($value) ? $value : throw TypeException::createFromValue($value, 'array<float>');
    }

    public static function stringArray(mixed $value): array
    {
        return Type::isStringArray($value) ? $value : throw TypeException::createFromValue($value, 'array<string>');
    }

    public static function arrayArray(mixed $value): array
    {
        return Type::isArrayArray($value) ? $value : throw TypeException::createFromValue($value, 'array<array>');
    }

    public static function listArray(mixed $value): array
    {
        return Type::isListArray($value) ? $value : throw TypeException::createFromValue($value, 'array<list>');
    }

    public static function objectArray(mixed $value): array
    {
        return Type::isObjectArray($value) ? $value : throw TypeException::createFromValue($value, 'array<object>');
    }

    public static function instanceArray(mixed $value, string $class): array
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        return Type::isInstanceArray($value, $class)
            ? $value
            : throw TypeException::createFromValue($value, "array<$class>");
    }

    public static function iterableArray(mixed $value): array
    {
        return Type::isIterableArray($value) ? $value : throw TypeException::createFromValue($value, 'array<iterable>');
    }

    public static function resourceArray(mixed $value): array
    {
        return Type::isResourceArray($value) ? $value : throw TypeException::createFromValue($value, 'array<resource>');
    }

    public static function callableArray(mixed $value): array
    {
        return Type::isCallableArray($value) ? $value : throw TypeException::createFromValue($value, 'array<callable>');
    }
}
