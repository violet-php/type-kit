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
    /**
     * @param mixed $value
     * @return list<null>
     * @throws CastException
     */
    public static function nullList(mixed $value): array
    {
        return self::handleListCast(Cast::null(...), $value, 'list<null>');
    }

    /**
     * @param mixed $value
     * @return list<bool>
     * @throws CastException
     */
    public static function boolList(mixed $value): array
    {
        return self::handleListCast(Cast::bool(...), $value, 'list<bool>');
    }

    /**
     * @param mixed $value
     * @return list<int>
     * @throws CastException
     */
    public static function intList(mixed $value): array
    {
        return self::handleListCast(Cast::int(...), $value, 'list<int>');
    }

    /**
     * @param mixed $value
     * @return list<float>
     * @throws CastException
     */
    public static function floatList(mixed $value): array
    {
        return self::handleListCast(Cast::float(...), $value, 'list<float>');
    }

    /**
     * @param mixed $value
     * @return list<string>
     * @throws CastException
     */
    public static function stringList(mixed $value): array
    {
        return self::handleListCast(Cast::string(...), $value, 'list<string>');
    }

    /**
     * @param mixed $value
     * @return list<array<mixed>>
     * @throws CastException
     */
    public static function arrayList(mixed $value): array
    {
        return self::handleListCast(Cast::array(...), $value, 'list<array>');
    }

    /**
     * @param mixed $value
     * @return list<list<mixed>>
     * @throws CastException
     */
    public static function listList(mixed $value): array
    {
        return self::handleListCast(Cast::list(...), $value, 'list<list>');
    }

    /**
     * @param mixed $value
     * @return list<object>
     * @throws CastException
     */
    public static function objectList(mixed $value): array
    {
        return self::handleListCast(Cast::object(...), $value, 'list<object>');
    }

    /**
     * @template T
     * @param mixed $value
     * @param class-string<T> $class
     * @return list<T>
     * @throws CastException
     */
    public static function instanceList(mixed $value, string $class): array
    {
        if (!class_exists($class) && !interface_exists($class)) {
            throw InvalidClassException::createFromName($class);
        }

        return self::handleListCast(static fn ($value) => Cast::instance($value, $class), $value, "list<$class>");
    }

    /**
     * @param mixed $value
     * @return list<iterable<mixed>>
     * @throws CastException
     */
    public static function iterableList(mixed $value): array
    {
        return self::handleListCast(Cast::iterable(...), $value, 'list<iterable>');
    }

    /**
     * @param mixed $value
     * @return list<resource>
     * @throws CastException
     */
    public static function resourceList(mixed $value): array
    {
        return self::handleListCast(Cast::resource(...), $value, 'list<resource>');
    }

    /**
     * @param mixed $value
     * @return list<callable>
     * @throws CastException
     */
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
