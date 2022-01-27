<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\InvalidClassException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeIs
{
    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true null $value
     */
    public static function null(mixed $value): bool
    {
        return $value === null;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true bool $value
     */
    public static function bool(mixed $value): bool
    {
        return \is_bool($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true int $value
     */
    public static function int(mixed $value): bool
    {
        return \is_int($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true float $value
     */
    public static function float(mixed $value): bool
    {
        return \is_float($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true string $value
     */
    public static function string(mixed $value): bool
    {
        return \is_string($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<mixed> $value
     */
    public static function array(mixed $value): bool
    {
        return \is_array($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<mixed> $value
     */
    public static function list(mixed $value): bool
    {
        return \is_array($value) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true object $value
     */
    public static function object(mixed $value): bool
    {
        return \is_object($value);
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return bool
     * @psalm-assert-if-true T $value
     */
    public static function instance(mixed $value, string $class): bool
    {
        if ($value instanceof $class) {
            return true;
        }

        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        return false;
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true iterable<mixed> $value
     */
    public static function iterable(mixed $value): bool
    {
        return \is_iterable($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true resource $value
     */
    public static function resource(mixed $value): bool
    {
        return \is_resource($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true callable $value
     */
    public static function callable(mixed $value): bool
    {
        return \is_callable($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true array<null> $value
     */
    public static function nullArray(mixed $value): bool
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
    public static function boolArray(mixed $value): bool
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
    public static function intArray(mixed $value): bool
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
    public static function floatArray(mixed $value): bool
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
    public static function stringArray(mixed $value): bool
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
    public static function arrayArray(mixed $value): bool
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
    public static function listArray(mixed $value): bool
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
    public static function objectArray(mixed $value): bool
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
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return bool
     * @psalm-assert-if-true array<T> $value
     */
    public static function instanceArray(mixed $value, string $class): bool
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
    public static function iterableArray(mixed $value): bool
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
    public static function resourceArray(mixed $value): bool
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
    public static function callableArray(mixed $value): bool
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

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<null> $value
     */
    public static function nullList(mixed $value): bool
    {
        return self::nullArray($value) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<bool> $value
     */
    public static function boolList(mixed $value): bool
    {
        return self::boolArray($value) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<int> $value
     */
    public static function intList(mixed $value): bool
    {
        return self::intArray($value) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<float> $value
     */
    public static function floatList(mixed $value): bool
    {
        return self::floatArray($value) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<string> $value
     */
    public static function stringList(mixed $value): bool
    {
        return self::stringArray($value) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<array<mixed>> $value
     */
    public static function arrayList(mixed $value): bool
    {
        return self::arrayArray($value) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<list<mixed>> $value
     */
    public static function listList(mixed $value): bool
    {
        return self::listArray($value) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<object> $value
     */
    public static function objectList(mixed $value): bool
    {
        return self::objectArray($value) && array_is_list($value);
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return bool
     * @psalm-assert-if-true list<T> $value
     */
    public static function instanceList(mixed $value, string $class): bool
    {
        return self::instanceArray($value, $class) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<iterable<mixed>> $value
     */
    public static function iterableList(mixed $value): bool
    {
        return self::iterableArray($value) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<resource> $value
     */
    public static function resourceList(mixed $value): bool
    {
        return self::resourceArray($value) && array_is_list($value);
    }

    /**
     * @param mixed $value
     * @return bool
     * @psalm-assert-if-true list<callable> $value
     */
    public static function callableList(mixed $value): bool
    {
        return self::callableArray($value) && array_is_list($value);
    }
}
