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
trait CastListTypesTrait
{
    public static function nullList(mixed $value): array
    {
        return self::handleListCast(Cast::null(...), $value, 'list<null>');
    }

    public static function boolList(mixed $value): array
    {
        return self::handleListCast(Cast::bool(...), $value, 'list<bool>');
    }

    public static function intList(mixed $value): array
    {
        return self::handleListCast(Cast::int(...), $value, 'list<int>');
    }

    public static function floatList(mixed $value): array
    {
        return self::handleListCast(Cast::float(...), $value, 'list<float>');
    }

    public static function stringList(mixed $value): array
    {
        return self::handleListCast(Cast::string(...), $value, 'list<string>');
    }

    public static function arrayList(mixed $value): array
    {
        return self::handleListCast(Cast::array(...), $value, 'list<array>');
    }

    public static function listList(mixed $value): array
    {
        return self::handleListCast(Cast::list(...), $value, 'list<list>');
    }

    public static function objectList(mixed $value): array
    {
        return self::handleListCast(Cast::object(...), $value, 'list<object>');
    }

    public static function instanceList(mixed $value, string $class): array
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        return self::handleListCast(static fn ($value) => Cast::instance($value, $class), $value, "list<$class>");
    }

    public static function iterableList(mixed $value): array
    {
        return self::handleListCast(Cast::iterable(...), $value, 'list<iterable>');
    }

    public static function resourceList(mixed $value): array
    {
        return self::handleListCast(Cast::resource(...), $value, 'list<resource>');
    }

    public static function callableList(mixed $value): array
    {
        return self::handleListCast(Cast::callable(...), $value, 'list<callable>');
    }

    private static function handleListCast(\Closure $cast, mixed $value, string $expectedType): array
    {
        try {
            $result = array_values(Cast::array($value));

            foreach ($result as $key => $item) {
                $result[$key] = $cast($item);
            }

            return $result;
        } catch (\Throwable $exception) {
            throw CastException::createFromFailure($value, $expectedType, $exception);
        }
    }
}
