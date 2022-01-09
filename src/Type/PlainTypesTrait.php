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
    public static function null(mixed $value): mixed
    {
        return $value === null ? null : throw TypeException::createFromValue($value, 'null');
    }

    public static function bool(mixed $value): bool
    {
        return \is_bool($value) ? $value : throw TypeException::createFromValue($value, 'bool');
    }

    public static function int(mixed $value): int
    {
        return \is_int($value) ? $value : throw TypeException::createFromValue($value, 'int');
    }

    public static function float(mixed $value): float
    {
        return \is_float($value) ? $value : throw TypeException::createFromValue($value, 'float');
    }

    public static function string(mixed $value): string
    {
        return \is_string($value) ? $value : throw TypeException::createFromValue($value, 'string');
    }

    public static function array(mixed $value): array
    {
        return \is_array($value) ? $value : throw TypeException::createFromValue($value, 'array');
    }

    public static function list(mixed $value): array
    {
        return \is_array($value) && array_is_list($value)
            ? $value
            : throw TypeException::createFromValue($value, 'list');
    }

    public static function object(mixed $value): object
    {
        return \is_object($value) ? $value : throw TypeException::createFromValue($value, 'object');
    }

    public static function instance(mixed $value, string $class): object
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        return $value instanceof $class ? $value : throw TypeException::createFromValue($value, $class);
    }

    public static function iterable(mixed $value): iterable
    {
        return is_iterable($value) ? $value : throw TypeException::createFromValue($value, 'iterable');
    }

    public static function resource(mixed $value): mixed
    {
        return \is_resource($value) ? $value : throw TypeException::createFromValue($value, 'resource');
    }

    public static function callable(mixed $value): callable
    {
        return \is_callable($value) ? $value : throw TypeException::createFromValue($value, 'callable');
    }
}
