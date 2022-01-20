<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\AssertException;
use Violet\TypeKit\Type;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait AssertListTypesTrait
{
    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<null> $value
     */
    public static function nullList(mixed $value): void
    {
        if (!Type::isNullList($value)) {
            throw AssertException::createFromValue($value, 'list<null>');
        }
    }

    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<bool> $value
     */
    public static function boolList(mixed $value): void
    {
        if (!Type::isBoolList($value)) {
            throw AssertException::createFromValue($value, 'list<bool>');
        }
    }

    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<int> $value
     */
    public static function intList(mixed $value): void
    {
        if (!Type::isIntList($value)) {
            throw AssertException::createFromValue($value, 'list<int>');
        }
    }

    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<float> $value
     */
    public static function floatList(mixed $value): void
    {
        if (!Type::isFloatList($value)) {
            throw AssertException::createFromValue($value, 'list<float>');
        }
    }

    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<string> $value
     */
    public static function stringList(mixed $value): void
    {
        if (!Type::isStringList($value)) {
            throw AssertException::createFromValue($value, 'list<string>');
        }
    }

    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<array<mixed>> $value
     */
    public static function arrayList(mixed $value): void
    {
        if (!Type::isArrayList($value)) {
            throw AssertException::createFromValue($value, 'list<array>');
        }
    }

    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<list<mixed>> $value
     */
    public static function listList(mixed $value): void
    {
        if (!Type::isListList($value)) {
            throw AssertException::createFromValue($value, 'list<list>');
        }
    }

    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<object> $value
     */
    public static function objectList(mixed $value): void
    {
        if (!Type::isObjectList($value)) {
            throw AssertException::createFromValue($value, 'list<object>');
        }
    }

    /**
     * @template T
     * @param mixed $value
     * @param class-string<T> $class
     * @throws AssertException
     * @psalm-assert list<T> $value
     */
    public static function instanceList(mixed $value, string $class): void
    {
        if (!Type::isInstanceList($value, $class)) {
            throw AssertException::createFromValue($value, "list<$class>");
        }
    }

    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<iterable<mixed>> $value
     */
    public static function iterableList(mixed $value): void
    {
        if (!Type::isIterableList($value)) {
            throw AssertException::createFromValue($value, 'list<iterable>');
        }
    }

    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<resource> $value
     */
    public static function resourceList(mixed $value): void
    {
        if (!Type::isResourceList($value)) {
            throw AssertException::createFromValue($value, 'list<resource>');
        }
    }

    /**
     * @param mixed $value
     * @throws AssertException
     * @psalm-assert list<callable> $value
     */
    public static function callableList(mixed $value): void
    {
        if (!Type::isCallableList($value)) {
            throw AssertException::createFromValue($value, 'list<callable>');
        }
    }
}
