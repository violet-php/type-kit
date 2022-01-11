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
    public static function nullArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::null(...), $value, 'array<null>');
    }

    public static function boolArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::bool(...), $value, 'array<bool>');
    }

    public static function intArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::int(...), $value, 'array<int>');
    }

    public static function floatArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::float(...), $value, 'array<float>');
    }

    public static function stringArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::string(...), $value, 'array<string>');
    }

    public static function arrayArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::array(...), $value, 'array<array>');
    }

    public static function listArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::list(...), $value, 'array<list>');
    }

    public static function objectArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::object(...), $value, 'array<object>');
    }

    public static function instanceArray(mixed $value, string $class): array
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        return self::handleArrayCast(static fn ($value) => Cast::instance($value, $class), $value, "array<$class>");
    }

    public static function iterableArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::iterable(...), $value, 'array<iterable>');
    }

    public static function resourceArray(mixed $value): array
    {
        return self::handleArrayCast(Cast::resource(...), $value, 'array<resource>');
    }

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
