<?php

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Debug\ErrorHandler;
use Violet\TypeKit\Exception\TypeCastException;
use Violet\TypeKit\Exception\InvalidClassException;

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
     * @throws TypeCastException
     */
    public static function null(mixed $value): mixed
    {
        return self::handlePlainCast(static fn (): mixed => null, $value, 'null');
    }

    /**
     * @param mixed $value
     * @return bool
     * @throws TypeCastException
     */
    public static function bool(mixed $value): bool
    {
        return self::handlePlainCast(static fn (): bool => $value, $value, 'bool');
    }

    /**
     * @param mixed $value
     * @return int
     * @throws TypeCastException
     */
    public static function int(mixed $value): int
    {
        return self::handlePlainCast(static fn (): int => $value, $value, 'int');
    }

    /**
     * @param mixed $value
     * @return float
     * @throws TypeCastException
     */
    public static function float(mixed $value): float
    {
        return self::handlePlainCast(static fn (): float => $value, $value, 'float');
    }

    /**
     * @param mixed $value
     * @return string
     * @throws TypeCastException
     */
    public static function string(mixed $value): string
    {
        return self::handlePlainCast(static fn (): string => $value, $value, 'string');
    }

    /**
     * @param mixed $value
     * @return array<mixed>
     * @throws TypeCastException
     */
    public static function array(mixed $value): array
    {
        if ($value instanceof \Traversable) {
            return self::handlePlainCast(static fn (): array => iterator_to_array($value), $value, 'array');
        }

        if (\is_object($value)) {
            return self::handlePlainCast(static fn (): array => get_object_vars($value), $value, 'array');
        }

        return self::handlePlainCast(static fn (): array => $value, $value, 'array');
    }

    /**
     * @param mixed $value
     * @return list<mixed>
     * @throws TypeCastException
     */
    public static function list(mixed $value): array
    {
        return self::handlePlainCast(static fn (): array => array_values(self::array($value)), $value, 'list');
    }

    /**
     * @param mixed $value
     * @return object
     * @throws TypeCastException
     */
    public static function object(mixed $value): object
    {
        return self::handlePlainCast(static fn (): object => $value, $value, 'object');
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
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        $result = self::handlePlainCast(static fn (): object => $value, $value, $class);

        if (!$result instanceof $class) {
            throw TypeCastException::createFromMessage($value, $class, 'Cannot cast objects to other objects');
        }

        return $result;
    }

    /**
     * @param mixed $value
     * @return iterable<mixed>
     * @throws TypeCastException
     */
    public static function iterable(mixed $value): array
    {
        return self::handlePlainCast(static fn (): iterable => $value, $value, 'iterable');
    }

    /**
     * @param mixed $value
     * @return resource
     * @throws TypeCastException
     */
    public static function resource(mixed $value): mixed
    {
        return \is_resource($value)
            ? $value
            : throw TypeCastException::createFromMessage($value, 'resource', 'Cannot cast other types to resources');
    }

    /**
     * @param mixed $value
     * @return callable
     * @throws TypeCastException
     */
    public static function callable(mixed $value): callable
    {
        return self::handlePlainCast(static fn (): callable => $value, $value, 'callable');
    }

    private static function handlePlainCast(\Closure $cast, mixed $value, string $expectedType): mixed
    {
        try {
            return ErrorHandler::handleCall($cast);
        } catch (\Throwable $exception) {
            throw TypeCastException::createFromFailure($value, $expectedType, $exception);
        }
    }
}
