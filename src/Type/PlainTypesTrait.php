<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\InvalidClassException;
use Violet\TypeKit\Exception\TypeException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait PlainTypesTrait
{
    /**
     * @param mixed $value
     * @return null
     * @throws TypeException
     */
    public static function null(mixed $value): mixed
    {
        return $value === null ? null : throw TypeException::createFromValue($value, 'null');
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws TypeException
     */
    public static function bool(mixed $value): bool
    {
        return \is_bool($value) ? $value : throw TypeException::createFromValue($value, 'bool');
    }

    /**
     * @param mixed $value
     * @return int
     * @throws TypeException
     */
    public static function int(mixed $value): int
    {
        return \is_int($value) ? $value : throw TypeException::createFromValue($value, 'int');
    }

    /**
     * @param mixed $value
     * @return float
     * @throws TypeException
     */
    public static function float(mixed $value): float
    {
        return \is_float($value) ? $value : throw TypeException::createFromValue($value, 'float');
    }

    /**
     * @param mixed $value
     * @return string
     * @throws TypeException
     */
    public static function string(mixed $value): string
    {
        return \is_string($value) ? $value : throw TypeException::createFromValue($value, 'string');
    }

    /**
     * @param mixed $value
     * @return array<mixed>
     * @throws TypeException
     */
    public static function array(mixed $value): array
    {
        return \is_array($value) ? $value : throw TypeException::createFromValue($value, 'array');
    }

    /**
     * @param mixed $value
     * @return list<mixed>
     * @throws TypeException
     */
    public static function list(mixed $value): array
    {
        return \is_array($value) && array_is_list($value)
            ? $value
            : throw TypeException::createFromValue($value, 'list');
    }

    /**
     * @param mixed $value
     * @return object
     * @throws TypeException
     */
    public static function object(mixed $value): object
    {
        return \is_object($value) ? $value : throw TypeException::createFromValue($value, 'object');
    }

    /**
     * @template T of object
     * @param mixed $value
     * @param class-string<T> $class
     * @return T
     * @throws TypeException
     */
    public static function instance(mixed $value, string $class): object
    {
        if ($value instanceof $class) {
            return $value;
        }

        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        throw TypeException::createFromValue($value, $class);
    }

    /**
     * @param mixed $value
     * @return iterable<mixed>
     * @throws TypeException
     */
    public static function iterable(mixed $value): iterable
    {
        return is_iterable($value) ? $value : throw TypeException::createFromValue($value, 'iterable');
    }

    /**
     * @param mixed $value
     * @return resource
     * @throws TypeException
     */
    public static function resource(mixed $value): mixed
    {
        return \is_resource($value) ? $value : throw TypeException::createFromValue($value, 'resource');
    }

    /**
     * @param mixed $value
     * @return callable
     * @throws TypeException
     */
    public static function callable(mixed $value): callable
    {
        return \is_callable($value) ? $value : throw TypeException::createFromValue($value, 'callable');
    }
}
