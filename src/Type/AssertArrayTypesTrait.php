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
trait AssertArrayTypesTrait
{
    public static function nullArray(mixed $value): void
    {
        if (!Type::isNullArray($value)) {
            throw AssertException::createFromValue($value, 'array<null>');
        }
    }

    public static function boolArray(mixed $value): void
    {
        if (!Type::isBoolArray($value)) {
            throw AssertException::createFromValue($value, 'array<bool>');
        }
    }

    public static function intArray(mixed $value): void
    {
        if (!Type::isIntArray($value)) {
            throw AssertException::createFromValue($value, 'array<int>');
        }
    }

    public static function floatArray(mixed $value): void
    {
        if (!Type::isFloatArray($value)) {
            throw AssertException::createFromValue($value, 'array<float>');
        }
    }

    public static function stringArray(mixed $value): void
    {
        if (!Type::isStringArray($value)) {
            throw AssertException::createFromValue($value, 'array<string>');
        }
    }

    public static function arrayArray(mixed $value): void
    {
        if (!Type::isArrayArray($value)) {
            throw AssertException::createFromValue($value, 'array<array>');
        }
    }

    public static function listArray(mixed $value): void
    {
        if (!Type::isListArray($value)) {
            throw AssertException::createFromValue($value, 'array<list>');
        }
    }

    public static function objectArray(mixed $value): void
    {
        if (!Type::isObjectArray($value)) {
            throw AssertException::createFromValue($value, 'array<object>');
        }
    }

    public static function instanceArray(mixed $value, string $class): void
    {
        if (!Type::isInstanceArray($value, $class)) {
            throw AssertException::createFromValue($value, "array<$class>");
        }
    }

    public static function iterableArray(mixed $value): void
    {
        if (!Type::isIterableArray($value)) {
            throw AssertException::createFromValue($value, 'array<iterable>');
        }
    }

    public static function resourceArray(mixed $value): void
    {
        if (!Type::isResourceArray($value)) {
            throw AssertException::createFromValue($value, 'array<resource>');
        }
    }

    public static function callableArray(mixed $value): void
    {
        if (!Type::isCallableArray($value)) {
            throw AssertException::createFromValue($value, 'array<callable>');
        }
    }
}
