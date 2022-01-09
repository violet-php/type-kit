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
trait ListTypesTrait
{
    public static function nullList(mixed $value): array
    {
        return Type::isNullList($value) ? $value : throw TypeException::createFromValue($value, 'list<null>');
    }

    public static function boolList(mixed $value): array
    {
        return Type::isBoolList($value) ? $value : throw TypeException::createFromValue($value, 'list<bool>');
    }

    public static function intList(mixed $value): array
    {
        return Type::isIntList($value) ? $value : throw TypeException::createFromValue($value, 'list<int>');
    }

    public static function floatList(mixed $value): array
    {
        return Type::isFloatList($value) ? $value : throw TypeException::createFromValue($value, 'list<float>');
    }

    public static function stringList(mixed $value): array
    {
        return Type::isStringList($value) ? $value : throw TypeException::createFromValue($value, 'list<string>');
    }

    public static function arrayList(mixed $value): array
    {
        return Type::isArrayList($value) ? $value : throw TypeException::createFromValue($value, 'list<array>');
    }

    public static function listList(mixed $value): array
    {
        return Type::isListList($value) ? $value : throw TypeException::createFromValue($value, 'list<list>');
    }

    public static function objectList(mixed $value): array
    {
        return Type::isObjectList($value) ? $value : throw TypeException::createFromValue($value, 'list<object>');
    }

    public static function instanceList(mixed $value, string $class): array
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        return Type::isInstanceList($value, $class)
            ? $value
            : throw TypeException::createFromValue($value, "list<$class>");
    }

    public static function iterableList(mixed $value): array
    {
        return Type::isIterableList($value) ? $value : throw TypeException::createFromValue($value, 'list<iterable>');
    }

    public static function resourceList(mixed $value): array
    {
        return Type::isResourceList($value) ? $value : throw TypeException::createFromValue($value, 'list<resource>');
    }

    public static function callableList(mixed $value): array
    {
        return Type::isCallableList($value) ? $value : throw TypeException::createFromValue($value, 'list<callable>');
    }
}
