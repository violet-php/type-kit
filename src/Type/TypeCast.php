<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Debug\ErrorHandler;
use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\Exception\TypeCastException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
class TypeCast
{
    /**
     * @param mixed $value
     * @return null
     */
    public static function null(mixed $value): mixed
    {
        if ($value === null) {
            return $value;
        }

        return null;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function bool(mixed $value): bool
    {
        if (\is_bool($value)) {
            return $value;
        }

        if (\in_array($value, [null, 0, 0.0, '', []], true)) {
            return false;
        }

        return true;
    }

    /**
     * @param mixed $value
     * @return int
     * @throws TypeCastException
     */
    public static function int(mixed $value): int
    {
        if (\is_int($value)) {
            return $value;
        }

        if ($value === null) {
            return 0;
        }

        if (\is_bool($value)) {
            return $value ? 1 : 0;
        }

        if (\is_float($value)) {
            $result = (int)$value;

            if ($value !== (float)$result) {
                throw TypeCastException::createFromMessage($value, 'int', 'Casting caused a loss of precision');
            }

            return $result;
        }

        if (\is_string($value)) {
            if (!is_numeric($value)) {
                throw TypeCastException::createFromMessage($value, 'int', 'Not a numeric string');
            }

            $result = $value * 1;

            if (\is_int($result)) {
                return $result;
            }

            $integer = (int)$result;

            if ($result !== (float)$integer) {
                throw TypeCastException::createFromMessage($value, 'int', 'Casting caused a loss of precision');
            }

            return $integer;
        }

        throw TypeCastException::createFromInvalidType($value, 'int');
    }

    /**
     * @param mixed $value
     * @return float
     * @throws TypeCastException
     */
    public static function float(mixed $value): float
    {
        if (\is_float($value)) {
            return $value;
        }

        if ($value === null) {
            return 0.0;
        }

        if (\is_bool($value)) {
            return $value ? 1.0 : 0.0;
        }

        if (\is_int($value)) {
            $result = (float)$value;

            if ($value !== (int)$result) {
                throw TypeCastException::createFromMessage($value, 'float', 'Casting caused a loss of precision');
            }

            return $result;
        }

        if (\is_string($value)) {
            if (!is_numeric($value)) {
                throw TypeCastException::createFromMessage($value, 'float', 'Not a numeric string');
            }

            $result = $value * 1;

            if (\is_float($result)) {
                return $result;
            }

            $integer = (float)$result;

            if ($result !== (int)$integer) {
                throw TypeCastException::createFromMessage($value, 'float', 'Casting caused a loss of precision');
            }

            return $integer;
        }

        throw TypeCastException::createFromInvalidType($value, 'float');
    }

    /**
     * @param mixed $value
     * @return string
     * @throws TypeCastException
     */
    public static function string(mixed $value): string
    {
        if (\is_string($value)) {
            return $value;
        }

        if ($value === null) {
            return '';
        }

        if (\is_bool($value)) {
            return $value ? '1' : '';
        }

        if (\is_int($value) || \is_float($value)) {
            return (string)$value;
        }

        if (\is_object($value)) {
            if (method_exists($value, '__toString')) {
                return (string)$value;
            }

            throw TypeCastException::createFromMessage(
                $value,
                'string',
                'Cannot cast an object without __toString() method to string'
            );
        }

        throw TypeCastException::createFromInvalidType($value, 'string');
    }

    /**
     * @param mixed $value
     * @return array<mixed>
     * @throws TypeCastException
     */
    public static function array(mixed $value): array
    {
        if (\is_array($value)) {
            return $value;
        }

        if ($value instanceof \Traversable) {
            try {
                return ErrorHandler::handleCall(static fn (): array => iterator_to_array($value));
            } catch (\Throwable $exception) {
                throw TypeCastException::createFromFailure($value, 'array', $exception);
            }
        }

        if (\is_object($value)) {
            return get_object_vars($value);
        }

        throw TypeCastException::createFromInvalidType($value, 'array');
    }

    /**
     * @param mixed $value
     * @return list<mixed>
     * @throws TypeCastException
     */
    public static function list(mixed $value): array
    {
        try {
            $array = self::array($value);
        } catch (\Throwable $exception) {
            throw TypeCastException::createFromFailure($value, 'list', $exception);
        }

        return array_values($array);
    }

    /**
     * @param mixed $value
     * @return object
     * @throws TypeCastException
     */
    public static function object(mixed $value): object
    {
        if (\is_object($value)) {
            return $value;
        }

        if (\is_array($value)) {
            return (object)$value;
        }

        throw TypeCastException::createFromInvalidType($value, 'object');
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return T
     * @throws TypeCastException
     */
    public static function instance(mixed $value, string $class): object
    {
        if ($value instanceof $class) {
            return $value;
        }

        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        throw TypeCastException::createFromInvalidType($value, $class);
    }

    /**
     * @param mixed $value
     * @return iterable<mixed>
     * @throws TypeCastException
     */
    public static function iterable(mixed $value): iterable
    {
        if (is_iterable($value)) {
            return $value;
        }

        throw TypeCastException::createFromInvalidType($value, 'iterable');
    }

    /**
     * @param mixed $value
     * @return resource
     * @throws TypeCastException
     */
    public static function resource(mixed $value): mixed
    {
        if (\is_resource($value)) {
            return $value;
        }

        throw TypeCastException::createFromInvalidType($value, 'resource');
    }

    /**
     * @param mixed $value
     * @return callable
     * @throws TypeCastException
     */
    public static function callable(mixed $value): callable
    {
        if (\is_callable($value)) {
            return $value;
        }

        throw TypeCastException::createFromInvalidType($value, 'callable');
    }

    /**
     * @param mixed $value
     * @return array<null>
     * @throws TypeCastException
     */
    public static function nullArray(mixed $value): array
    {
        return TypeIs::nullArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): mixed => self::null($x), $value, 'array<null>');
    }

    /**
     * @param mixed $value
     * @return array<bool>
     * @throws TypeCastException
     */
    public static function boolArray(mixed $value): array
    {
        return TypeIs::boolArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): bool => self::bool($x), $value, 'array<bool>');
    }

    /**
     * @param mixed $value
     * @return array<int>
     * @throws TypeCastException
     */
    public static function intArray(mixed $value): array
    {
        return TypeIs::intArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): int => self::int($x), $value, 'array<int>');
    }

    /**
     * @param mixed $value
     * @return array<float>
     * @throws TypeCastException
     */
    public static function floatArray(mixed $value): array
    {
        return TypeIs::floatArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): float => self::float($x), $value, 'array<float>');
    }

    /**
     * @param mixed $value
     * @return array<string>
     * @throws TypeCastException
     */
    public static function stringArray(mixed $value): array
    {
        return TypeIs::stringArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): string => self::string($x), $value, 'array<string>');
    }

    /**
     * @param mixed $value
     * @return array<array<mixed>>
     * @throws TypeCastException
     */
    public static function arrayArray(mixed $value): array
    {
        return TypeIs::arrayArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): array => self::array($x), $value, 'array<array>');
    }

    /**
     * @param mixed $value
     * @return array<list<mixed>>
     * @throws TypeCastException
     */
    public static function listArray(mixed $value): array
    {
        return TypeIs::listArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): array => self::list($x), $value, 'array<list>');
    }

    /**
     * @param mixed $value
     * @return array<object>
     * @throws TypeCastException
     */
    public static function objectArray(mixed $value): array
    {
        return TypeIs::objectArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): object => self::object($x), $value, 'array<object>');
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return array<T>
     * @throws TypeCastException
     */
    public static function instanceArray(mixed $value, string $class): array
    {
        return TypeIs::instanceArray($value, $class)
            ? $value
            : self::castArray(static fn (mixed $x): object => self::instance($x, $class), $value, "array<$class>");
    }

    /**
     * @param mixed $value
     * @return array<iterable<mixed>>
     * @throws TypeCastException
     */
    public static function iterableArray(mixed $value): array
    {
        return TypeIs::iterableArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): iterable => self::iterable($x), $value, 'array<iterable>');
    }

    /**
     * @param mixed $value
     * @return array<resource>
     * @throws TypeCastException
     */
    public static function resourceArray(mixed $value): array
    {
        return TypeIs::resourceArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): mixed => self::resource($x), $value, 'array<resource>');
    }

    /**
     * @param mixed $value
     * @return array<callable>
     * @throws TypeCastException
     */
    public static function callableArray(mixed $value): array
    {
        return TypeIs::callableArray($value)
            ? $value
            : self::castArray(static fn (mixed $x): callable => self::callable($x), $value, 'array<callable>');
    }

    /**
     * @param mixed $value
     * @return list<null>
     * @throws TypeCastException
     */
    public static function nullList(mixed $value): array
    {
        return TypeIs::nullList($value)
            ? $value
            : self::castList(static fn (mixed $x): mixed => self::null($x), $value, 'list<null>');
    }

    /**
     * @param mixed $value
     * @return list<bool>
     * @throws TypeCastException
     */
    public static function boolList(mixed $value): array
    {
        return TypeIs::boolList($value)
            ? $value
            : self::castList(static fn (mixed $x): bool => self::bool($x), $value, 'list<bool>');
    }

    /**
     * @param mixed $value
     * @return list<int>
     * @throws TypeCastException
     */
    public static function intList(mixed $value): array
    {
        return TypeIs::intList($value)
            ? $value
            : self::castList(static fn (mixed $x): int => self::int($x), $value, 'list<int>');
    }

    /**
     * @param mixed $value
     * @return list<float>
     * @throws TypeCastException
     */
    public static function floatList(mixed $value): array
    {
        return TypeIs::floatList($value)
            ? $value
            : self::castList(static fn (mixed $x): float => self::float($x), $value, 'list<float>');
    }

    /**
     * @param mixed $value
     * @return list<string>
     * @throws TypeCastException
     */
    public static function stringList(mixed $value): array
    {
        return TypeIs::stringList($value)
            ? $value
            : self::castList(static fn (mixed $x): string => self::string($x), $value, 'list<string>');
    }

    /**
     * @param mixed $value
     * @return list<array<mixed>>
     * @throws TypeCastException
     */
    public static function arrayList(mixed $value): array
    {
        return TypeIs::arrayList($value)
            ? $value
            : self::castList(static fn (mixed $x): array => self::array($x), $value, 'list<array>');
    }

    /**
     * @param mixed $value
     * @return list<list<mixed>>
     * @throws TypeCastException
     */
    public static function listList(mixed $value): array
    {
        return TypeIs::listList($value)
            ? $value
            : self::castList(static fn (mixed $x): array => self::list($x), $value, 'list<list>');
    }

    /**
     * @param mixed $value
     * @return list<object>
     * @throws TypeCastException
     */
    public static function objectList(mixed $value): array
    {
        return TypeIs::objectList($value)
            ? $value
            : self::castList(static fn (mixed $x): object => self::object($x), $value, 'list<object>');
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return list<T>
     * @throws TypeCastException
     */
    public static function instanceList(mixed $value, string $class): array
    {
        return TypeIs::instanceList($value, $class)
            ? $value
            : self::castList(static fn (mixed $x): object => self::instance($x, $class), $value, "list<$class>");
    }

    /**
     * @param mixed $value
     * @return list<iterable<mixed>>
     * @throws TypeCastException
     */
    public static function iterableList(mixed $value): array
    {
        return TypeIs::iterableList($value)
            ? $value
            : self::castList(static fn (mixed $x): iterable => self::iterable($x), $value, 'list<iterable>');
    }

    /**
     * @param mixed $value
     * @return list<resource>
     * @throws TypeCastException
     */
    public static function resourceList(mixed $value): array
    {
        return TypeIs::resourceList($value)
            ? $value
            : self::castList(static fn (mixed $x): mixed => self::resource($x), $value, 'list<resource>');
    }

    /**
     * @param mixed $value
     * @return list<callable>
     * @throws TypeCastException
     */
    public static function callableList(mixed $value): array
    {
        return TypeIs::callableList($value)
            ? $value
            : self::castList(static fn (mixed $x): callable => self::callable($x), $value, 'list<callable>');
    }

    /**
     * @template T
     * @param \Closure(mixed):T $cast
     * @param mixed $value
     * @param string $expectedType
     * @return array<T>
     */
    private static function castArray(\Closure $cast, mixed $value, string $expectedType): array
    {
        try {
            return array_map($cast, self::array($value));
        } catch (\Throwable $exception) {
            throw TypeCastException::createFromFailure($value, $expectedType, $exception);
        }
    }

    /**
     * @template T
     * @param \Closure(mixed):T $cast
     * @param mixed $value
     * @param string $expectedType
     * @return list<T>
     */
    private static function castList(\Closure $cast, mixed $value, string $expectedType): array
    {
        return array_values(self::castArray($cast, $value, $expectedType));
    }
}
