<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Exception\TypeAssertException;
use Violet\TypeKit\Exception\InvalidClassException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait AssertPlainTypesTrait
{
    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert null $value
     */
    public static function null(mixed $value): void
    {
        if ($value !== null) {
            throw TypeAssertException::createFromValue($value, 'null');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert bool $value
     */
    public static function bool(mixed $value): void
    {
        if (!\is_bool($value)) {
            throw TypeAssertException::createFromValue($value, 'bool');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert int $value
     */
    public static function int(mixed $value): void
    {
        if (!\is_int($value)) {
            throw TypeAssertException::createFromValue($value, 'int');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert float $value
     */
    public static function float(mixed $value): void
    {
        if (!\is_float($value)) {
            throw TypeAssertException::createFromValue($value, 'float');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert string $value
     */
    public static function string(mixed $value): void
    {
        if (!\is_string($value)) {
            throw TypeAssertException::createFromValue($value, 'string');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert array<mixed> $value
     */
    public static function array(mixed $value): void
    {
        if (!\is_array($value)) {
            throw TypeAssertException::createFromValue($value, 'array');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert list<mixed> $value
     */
    public static function list(mixed $value): void
    {
        if (!\is_array($value) || !array_is_list($value)) {
            throw TypeAssertException::createFromValue($value, 'list');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert object $value
     */
    public static function object(mixed $value): void
    {
        if (!\is_object($value)) {
            throw TypeAssertException::createFromValue($value, 'object');
        }
    }

    /**
     * @template T
     * @param mixed $value
     * @param class-string<T> $class
     * @throws TypeAssertException
     * @psalm-assert T $value
     */
    public static function instance(mixed $value, string $class): void
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        if (!$value instanceof $class) {
            throw TypeAssertException::createFromValue($value, $class);
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert iterable<mixed> $value
     */
    public static function iterable(mixed $value): void
    {
        if (!\is_iterable($value)) {
            throw TypeAssertException::createFromValue($value, 'iterable');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert resource $value
     */
    public static function resource(mixed $value): void
    {
        if (!\is_resource($value)) {
            throw TypeAssertException::createFromValue($value, 'resource');
        }
    }

    /**
     * @param mixed $value
     * @throws TypeAssertException
     * @psalm-assert callable $value
     */
    public static function callable(mixed $value): void
    {
        if (!\is_callable($value)) {
            throw TypeAssertException::createFromValue($value, 'callable');
        }
    }
}
