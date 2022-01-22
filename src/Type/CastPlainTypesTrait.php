<?php

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Debug\ErrorHandler;
use Violet\TypeKit\Exception\TypeCastException;
use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\TypeCast;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait CastPlainTypesTrait
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

            $result = $value + 0;

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

            $result = $value + 0;

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
            return ErrorHandler::handleCall(static fn (): array => array_values(TypeCast::array($value)));
        } catch (\Throwable $exception) {
            throw TypeCastException::createFromFailure($value, 'list', $exception);
        }
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
     * @template T
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
}
