<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\TypeCast;
use Violet\TypeKit\Exception\TypeCastException;
use Violet\TypeKit\Exception\InvalidClassException;

/**
 * @author Riikka Kalliomäki <riikka.kalliomaki@gmail.com>
 * @copyright Copyright (c) 2022 Riikka Kalliomäki
 * @license http://opensource.org/licenses/mit-license.php MIT License
 */
trait CastArrayTypesTrait
{
    /**
     * @param mixed $value
     * @return array<null>
     * @throws TypeCastException
     */
    public static function nullArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::null(...), $value, 'array<null>');
    }

    /**
     * @param mixed $value
     * @return array<bool>
     * @throws TypeCastException
     */
    public static function boolArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::bool(...), $value, 'array<bool>');
    }

    /**
     * @param mixed $value
     * @return array<int>
     * @throws TypeCastException
     */
    public static function intArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::int(...), $value, 'array<int>');
    }

    /**
     * @param mixed $value
     * @return array<float>
     * @throws TypeCastException
     */
    public static function floatArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::float(...), $value, 'array<float>');
    }

    /**
     * @param mixed $value
     * @return array<string>
     * @throws TypeCastException
     */
    public static function stringArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::string(...), $value, 'array<string>');
    }

    /**
     * @param mixed $value
     * @return array<array<mixed>>
     * @throws TypeCastException
     */
    public static function arrayArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::array(...), $value, 'array<array>');
    }

    /**
     * @param mixed $value
     * @return array<list<mixed>>
     * @throws TypeCastException
     */
    public static function listArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::list(...), $value, 'array<list>');
    }

    /**
     * @param mixed $value
     * @return array<object>
     * @throws TypeCastException
     */
    public static function objectArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::object(...), $value, 'array<object>');
    }

    /**
     * @template T
     * @param mixed $value
     * @param class-string<T> $class
     * @return array<T>
     * @throws TypeCastException
     */
    public static function instanceArray(mixed $value, string $class): array
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        return self::handleArrayCast(static fn ($value) => TypeCast::instance($value, $class), $value, "array<$class>");
    }

    /**
     * @param mixed $value
     * @return array<iterable<mixed>>
     * @throws TypeCastException
     */
    public static function iterableArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::iterable(...), $value, 'array<iterable>');
    }

    /**
     * @param mixed $value
     * @return array<resource>
     * @throws TypeCastException
     */
    public static function resourceArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::resource(...), $value, 'array<resource>');
    }

    /**
     * @param mixed $value
     * @return array<callable>
     * @throws TypeCastException
     */
    public static function callableArray(mixed $value): array
    {
        return self::handleArrayCast(TypeCast::callable(...), $value, 'array<callable>');
    }

    private static function handleArrayCast(\Closure $cast, mixed $value, string $expectedType): array
    {
        try {
            $result = TypeCast::array($value);

            foreach ($result as $key => $item) {
                $result[$key] = $cast($item);
            }

            return $result;
        } catch (\Throwable $exception) {
            throw TypeCastException::createFromFailure($value, $expectedType, $exception);
        }
    }
}
