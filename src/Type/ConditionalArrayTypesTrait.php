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
