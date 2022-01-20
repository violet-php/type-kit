<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\TypeAssertException;
use Violet\TypeKit\Type;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait AssertArrayTypesTrait
{
    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<null> $value
     */
    public static function nullArray(mixed $value): void
    {
        if (!Type::isNullArray($value)) {
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
        if (!Type::isBoolArray($value)) {
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
        if (!Type::isIntArray($value)) {
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
        if (!Type::isFloatArray($value)) {
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
        if (!Type::isStringArray($value)) {
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
        if (!Type::isArrayArray($value)) {
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
        if (!Type::isListArray($value)) {
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
        if (!Type::isObjectArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<object>');
        }
    }

    /**
     * @template T
     * @param mixed $value
     * @param class-string<T> $class
     * @throws TypeAssertException
     * @psalm-assert array<T> $value
     */
    public static function instanceArray(mixed $value, string $class): void
    {
        if (!Type::isInstanceArray($value, $class)) {
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
        if (!Type::isIterableArray($value)) {
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
        if (!Type::isResourceArray($value)) {
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
        if (!Type::isCallableArray($value)) {
            throw TypeAssertException::createFromValue($value, 'array<callable>');
        }
    }
}
