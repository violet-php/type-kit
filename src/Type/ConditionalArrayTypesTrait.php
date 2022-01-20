<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\InvalidClassException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait ConditionalArrayTypesTrait
{
    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<null> $value
     */
    public static function isNullArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if ($item !== null) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<bool> $value
     */
    public static function isBoolArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!\is_bool($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<int> $value
     */
    public static function isIntArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!\is_int($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<float> $value
     */
    public static function isFloatArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!\is_float($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<string> $value
     */
    public static function isStringArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!\is_string($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<array<mixed>> $value
     */
    public static function isArrayArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!\is_array($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<list<mixed>> $value
     */
    public static function isListArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!\is_array($item) || !array_is_list($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<object> $value
     */
    public static function isObjectArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!\is_object($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @template T
     * @param mixed $value
     * @param class-string<T> $class
     * @return bool
     * @psalm-assert-if-true array<T> $value
     */
    public static function isInstanceArray(mixed $value, string $class): bool
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!$item instanceof $class) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<iterable<mixed>> $value
     */
    public static function isIterableArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!is_iterable($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<resource> $value
     */
    public static function isResourceArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!\is_resource($item)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<callable> $value
     */
    public static function isCallableArray(mixed $value): bool
    {
        if (!\is_array($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (!\is_callable($item)) {
                return false;
            }
        }

        return true;
    }
}
