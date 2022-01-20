<?php

declare(strict_types=1);

namespace Violet\TypeKit\Type;

use Violet\TypeKit\Cast;
use Violet\TypeKit\Exception\CastException;
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
     * @throws CastException
     */
    public static function nullArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::null(...), $value, 'array<null>');
    }

    /**
     * @param mixed $value
     * @return array<bool>
     * @throws CastException
     */
    public static function boolArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::bool(...), $value, 'array<bool>');
    }

    /**
     * @param mixed $value
     * @return array<int>
     * @throws CastException
     */
    public static function intArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::int(...), $value, 'array<int>');
    }

    /**
     * @param mixed $value
     * @return array<float>
     * @throws CastException
     */
    public static function floatArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::float(...), $value, 'array<float>');
    }

    /**
     * @param mixed $value
     * @return array<string>
     * @throws CastException
     */
    public static function stringArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::string(...), $value, 'array<string>');
    }

    /**
     * @param mixed $value
     * @return array<array<mixed>>
     * @throws CastException
     */
    public static function arrayArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::array(...), $value, 'array<array>');
    }

    /**
     * @param mixed $value
     * @return array<list<mixed>>
     * @throws CastException
     */
    public static function listArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::list(...), $value, 'array<list>');
    }

    /**
     * @param mixed $value
     * @return array<object>
     * @throws CastException
     */
    public static function objectArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::object(...), $value, 'array<object>');
    }

    /**
     * @template T
     * @param mixed $value
     * @param class-string<T> $class
     * @return array<T>
     * @throws CastException
     */
    public static function instanceArray(mixed $value, string $class): array
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        return self::handleArrayCast(static fn ($value) => Cast::instance($value, $class), $value, "array<$class>");
    }

    /**
     * @param mixed $value
     * @return array<iterable<mixed>>
     * @throws CastException
     */
    public static function iterableArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::iterable(...), $value, 'array<iterable>');
    }

    /**
     * @param mixed $value
     * @return array<resource>
     * @throws CastException
     */
    public static function resourceArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::resource(...), $value, 'array<resource>');
    }

    /**
     * @param mixed $value
     * @return array<callable>
     * @throws CastException
     */
    public static function callableArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::callable(...), $value, 'array<callable>');
    }

    private static function handleArrayCast(\Closure $cast, mixed $value, string $expectedType): array
    {
        try {
            $result = Cast::array($value);

            foreach ($result as $key => $item) {
                $result[$key] = $cast($item);
            }

            return $result;
        } catch (\Throwable $exception) {
            throw CastException::createFromFailure($value, $expectedType, $exception);
        }
    }
}
