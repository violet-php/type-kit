<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\AssertException;
use Violet\TypeKit\Exception\InvalidClassException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait AssertPlainTypesTrait
{
    public static function null(mixed $value): void
    {
        if ($value !== null) {
            throw AssertException::createFromValue($value, 'null');
        }
    }

    public static function bool(mixed $value): void
    {
        if (!\is_bool($value)) {
            throw AssertException::createFromValue($value, 'bool');
        }
    }

    public static function int(mixed $value): void
    {
        if (!\is_int($value)) {
            throw AssertException::createFromValue($value, 'int');
        }
    }

    public static function float(mixed $value): void
    {
        if (!\is_float($value)) {
            throw AssertException::createFromValue($value, 'float');
        }
    }

    public static function string(mixed $value): void
    {
        if (!\is_string($value)) {
            throw AssertException::createFromValue($value, 'string');
        }
    }

    public static function array(mixed $value): void
    {
        if (!\is_array($value)) {
            throw AssertException::createFromValue($value, 'array');
        }
    }

    public static function list(mixed $value): void
    {
        if (!\is_array($value) || !array_is_list($value)) {
            throw AssertException::createFromValue($value, 'list');
        }
    }

    public static function object(mixed $value): void
    {
        if (!\is_object($value)) {
            throw AssertException::createFromValue($value, 'object');
        }
    }

    public static function instance(mixed $value, string $class): void
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        if (!$value instanceof $class) {
            throw AssertException::createFromValue($value, $class);
        }
    }

    public static function iterable(mixed $value): void
    {
        if (!\is_iterable($value)) {
            throw AssertException::createFromValue($value, 'iterable');
        }
    }

    public static function resource(mixed $value): void
    {
        if (!\is_resource($value)) {
            throw AssertException::createFromValue($value, 'resource');
        }
    }

    public static function callable(mixed $value): void
    {
        if (!\is_callable($value)) {
            throw AssertException::createFromValue($value, 'callable');
        }
    }
}
