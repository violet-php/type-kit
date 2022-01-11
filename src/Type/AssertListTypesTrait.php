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
    public static function nullList(mixed $value): void
    {
        if (!Type::isNullList($value)) {
            throw AssertException::createFromValue($value, 'list<null>');
        }
    }

    public static function boolList(mixed $value): void
    {
        if (!Type::isBoolList($value)) {
            throw AssertException::createFromValue($value, 'list<bool>');
        }
    }

    public static function intList(mixed $value): void
    {
        if (!Type::isIntList($value)) {
            throw AssertException::createFromValue($value, 'list<int>');
        }
    }

    public static function floatList(mixed $value): void
    {
        if (!Type::isFloatList($value)) {
            throw AssertException::createFromValue($value, 'list<float>');
        }
    }

    public static function stringList(mixed $value): void
    {
        if (!Type::isStringList($value)) {
            throw AssertException::createFromValue($value, 'list<string>');
        }
    }

    public static function arrayList(mixed $value): void
    {
        if (!Type::isArrayList($value)) {
            throw AssertException::createFromValue($value, 'list<array>');
        }
    }

    public static function listList(mixed $value): void
    {
        if (!Type::isListList($value)) {
            throw AssertException::createFromValue($value, 'list<list>');
        }
    }

    public static function objectList(mixed $value): void
    {
        if (!Type::isObjectList($value)) {
            throw AssertException::createFromValue($value, 'list<object>');
        }
    }

    public static function instanceList(mixed $value, string $class): void
    {
        if (!Type::isInstanceList($value, $class)) {
            throw AssertException::createFromValue($value, "list<$class>");
        }
    }

    public static function iterableList(mixed $value): void
    {
        if (!Type::isIterableList($value)) {
            throw AssertException::createFromValue($value, 'list<iterable>');
        }
    }

    public static function resourceList(mixed $value): void
    {
        if (!Type::isResourceList($value)) {
            throw AssertException::createFromValue($value, 'list<resource>');
        }
    }

    public static function callableList(mixed $value): void
    {
        if (!Type::isCallableList($value)) {
            throw AssertException::createFromValue($value, 'list<callable>');
        }
    }
}
