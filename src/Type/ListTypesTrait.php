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
    /**
     * @param mixed $value
     * @return list<null>
     * @throws TypeException
     */
    public static function nullList(mixed $value): array
    {
        return Type::isNullList($value) ? $value : throw TypeException::createFromValue($value, 'list<null>');
    }

    /**
     * @param mixed $value
     * @return list<bool>
     * @throws TypeException
     */
    public static function boolList(mixed $value): array
    {
        return Type::isBoolList($value) ? $value : throw TypeException::createFromValue($value, 'list<bool>');
    }

    /**
     * @param mixed $value
     * @return list<int>
     * @throws TypeException
     */
    public static function intList(mixed $value): array
    {
        return Type::isIntList($value) ? $value : throw TypeException::createFromValue($value, 'list<int>');
    }

    /**
     * @param mixed $value
     * @return list<float>
     * @throws TypeException
     */
    public static function floatList(mixed $value): array
    {
        return Type::isFloatList($value) ? $value : throw TypeException::createFromValue($value, 'list<float>');
    }

    /**
     * @param mixed $value
     * @return list<string>
     * @throws TypeException
     */
    public static function stringList(mixed $value): array
    {
        return Type::isStringList($value) ? $value : throw TypeException::createFromValue($value, 'list<string>');
    }

    /**
     * @param mixed $value
     * @return list<array<mixed>>
     * @throws TypeException
     */
    public static function arrayList(mixed $value): array
    {
        return Type::isArrayList($value) ? $value : throw TypeException::createFromValue($value, 'list<array>');
    }

    /**
     * @param mixed $value
     * @return list<list<mixed>>
     * @throws TypeException
     */
    public static function listList(mixed $value): array
    {
        return Type::isListList($value) ? $value : throw TypeException::createFromValue($value, 'list<list>');
    }

    /**
     * @param mixed $value
     * @return list<object>
     * @throws TypeException
     */
    public static function objectList(mixed $value): array
    {
        return Type::isObjectList($value) ? $value : throw TypeException::createFromValue($value, 'list<object>');
    }

    /**
     * @template T
     * @param mixed $value
     * @param class-string<T> $class
     * @return list<T>
     * @throws TypeException
     */
    public static function instanceList(mixed $value, string $class): array
    {
        return Type::isInstanceList($value, $class)
            ? $value
            : throw TypeException::createFromValue($value, "list<$class>");
    }

    /**
     * @param mixed $value
     * @return list<iterable<mixed>>
     * @throws TypeException
     */
    public static function iterableList(mixed $value): array
    {
        return Type::isIterableList($value) ? $value : throw TypeException::createFromValue($value, 'list<iterable>');
    }

    /**
     * @param mixed $value
     * @return list<resource>
     * @throws TypeException
     */
    public static function resourceList(mixed $value): array
    {
        return Type::isResourceList($value) ? $value : throw TypeException::createFromValue($value, 'list<resource>');
    }

    /**
     * @param mixed $value
     * @return list<callable>
     * @throws TypeException
     */
    public static function callableList(mixed $value): array
    {
        return Type::isCallableList($value) ? $value : throw TypeException::createFromValue($value, 'list<callable>');
    }
}
