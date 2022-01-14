<?php

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Debug\ErrorHandler;
use Violet\TypeKit\Exception\CastException;
use Violet\TypeKit\Exception\InvalidClassException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait CastPlainTypesTrait
{
    public static function null(mixed $value): mixed
    {
        return self::handlePlainCast(static fn (): mixed => null, $value, 'null');
    }

    public static function bool(mixed $value): bool
    {
        return self::handlePlainCast(static fn (): bool => $value, $value, 'bool');
    }

    public static function int(mixed $value): int
    {
        return self::handlePlainCast(static fn (): int => $value, $value, 'int');
    }

    public static function float(mixed $value): float
    {
        return self::handlePlainCast(static fn (): float => $value, $value, 'float');
    }

    public static function string(mixed $value): string
    {
        return self::handlePlainCast(static fn (): string => $value, $value, 'string');
    }

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

    public static function list(mixed $value): array
    {
        return self::handlePlainCast(static fn (): array => array_values(self::array($value)), $value, 'list');
    }

    public static function object(mixed $value): object
    {
        return self::handlePlainCast(static fn (): object => $value, $value, 'object');
    }

    public static function instance(mixed $value, string $class): object
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        $result = self::handlePlainCast(static fn (): object => $value, $value, $class);

        if (!$result instanceof $class) {
            throw CastException::createFromMessage($value, $class, 'Cannot cast objects to other objects');
        }

        return $result;
    }

    public static function iterable(mixed $value): array
    {
        return self::handlePlainCast(static fn (): iterable => $value, $value, 'iterable');
    }

    public static function resource(mixed $value): mixed
    {
        return \is_resource($value)
            ? $value
            : throw CastException::createFromMessage($value, 'resource', 'Cannot cast other types to resources');
    }

    public static function callable(mixed $value): callable
    {
        return self::handlePlainCast(static fn (): callable => $value, $value, 'callable');
    }

    private static function handlePlainCast(\Closure $cast, mixed $value, string $expectedType): mixed
    {
        try {
            return ErrorHandler::handleCall($cast);
        } catch (\Throwable $exception) {
            throw CastException::createFromFailure($value, $expectedType, $exception);
        }
    }
}
